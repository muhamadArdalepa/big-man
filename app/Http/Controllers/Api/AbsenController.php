<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use App\Models\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        Carbon::setLocale('id');
    }

    public function index(Request $request)
    {
        switch (auth()->user()->role) {
            case 1:
                $query = User::select(
                    'id',
                    'nama',
                    'foto_profil'
                )
                    ->where('role', 2)
                    ->orderBy('nama', 'asc');

                if ($request->has('wilayah') && !empty($request->wilayah)) {
                    $query->where('wilayah_id', $request->wilayah);
                }

                $tanggal = date('Y-m-d');
                if ($request->has('tanggal') && !empty($request->tanggal)) {
                    $tanggal = $request->tanggal;
                }

                $users = $query->get();
                $users->map(function ($user) use ($tanggal) {
                    $absen = Absen::whereDate('created_at', $tanggal)
                        ->where('user_id', '=', $user->id)
                        ->first();
                    if ($absen) {
                        $user->absen_id = $absen->id;
                        $user->absens = $absen->getTimes();
                        $user->status = $absen->getStatus();
                    } else {
                        $absen = [null, null, null, null];
                        $user->absen_id = null;
                        $user->absens = $absen;
                        $user->status = ['Tidak hadir', 'bg-gradient-danger'];
                    }
                    return $user;
                });
                return response()->json($users);
            case 2:
                $id = auth()->user()->id;
                $query = Absen::where('user_id', $id);

                if ($request->has('month') && !empty($request->month)) {
                    $query->whereMonth('created_at', $request->month);
                }

                if ($request->has('year') && !empty($request->year)) {
                    $query->whereYear('created_at', $request->year);
                }

                $absens = $query->get();
                $absens->map(function ($absen) {
                    $absen->tanggalFormat = Carbon::parse($absen->created_at)->translatedFormat("l, j F Y");
                    $absen->absens = $absen->getTimes();
                    $absen->status = $absen->getStatus();
                    return $absen;
                });
                return response()->json($absens);
            default:
                return response()->json(['message' => 'Unauthorized']);
        }
    }

    public function all(Request $request)
    {
        switch (auth()->user()->role) {
            case 1:
                $query = User::select(
                    'id',
                    'nama',
                    'foto_profil'
                )
                    ->where('role', 2)
                    ->orderBy('nama', 'asc');

                if ($request->has('wilayah') && !empty($request->wilayah)) {
                    $query->where('wilayah_id', $request->wilayah);
                }

                $year = $request->year;
                $month = $request->month;
                $users = $query->get();

                $users->map(function ($user) use ($year, $month) {
                    $user->n = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $user->present = Absen::where('user_id', $user->id)
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)->where('status', 1)->count();
                    $user->late = Absen::where('user_id', $user->id)
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)->where('status', 2)->count();
                    $user->absent = Absen::where('user_id', $user->id)
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)->where('status', 3)->count();

                    $details = Absen::where('user_id', $user->id)
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)->get();
                    $de = [];

                    for ($i = 0; $i < 31; $i++) {
                        array_push($de, ['-', 'bg-gradient-light']);
                    }
                    if ($details) {
                        foreach ($details as $i => $detail) {
                            $de[(int)Carbon::parse($detail->created_at)->format('j') - 1] = $detail->getStatus();
                        }
                    }
                    $user->detail = $de;
                    return $user;
                });
                return response()->json($users);
            case 2:
                // ambil id
                $user_id = auth()->user()->id;

                if ($request->year == date('Y')) {
                    $month = date('n');
                } else {
                    $month = 12;
                }
                $absens = [];
                for ($i = 1; $i <= $month; $i++) {
                    $res = [
                        'i' => $i,
                        'bulan' => Carbon::createFromFormat('n', $i)->translatedFormat('F'),
                        'n' => cal_days_in_month(CAL_GREGORIAN, $i, $request->year),
                        'present' => Absen::where('user_id', $user_id)
                            ->whereYear('created_at', $request->year)
                            ->whereMonth('created_at', $i)->where('status', 1)->count(),
                        'late' => Absen::where('user_id', $user_id)
                            ->whereYear('created_at', $request->year)
                            ->whereMonth('created_at', $i)->where('status', 2)->count(),
                        'absent' => Absen::where('user_id', $user_id)
                            ->whereYear('created_at', $request->year)
                            ->whereMonth('created_at', $i)->where('status', 3)->count(),
                    ];
                    $details = Absen::where('user_id', $user_id)
                        ->whereYear('created_at', $request->year)
                        ->whereMonth('created_at', $i)->get();

                    for ($j = 0; $j < 31; $j++) {
                        array_push($res, ['-', 'bg-gradient-secondary']);
                    }
                    if ($details) {
                        foreach ($details as $j => $detail) {
                            $res[(int)Carbon::parse($detail->created_at)->format('j') - 1] = $detail->getStatus();
                        }
                    }
                    array_push($absens, $res);
                }
                return response()->json($absens);
            default:
                return response()->json(['message' => 'Unauthorized']);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'foto' => 'required',
            'aktivitas' => 'required',
            'koordinat' => 'required',
        ], [
            'foto.required' => 'Foto harus diisi',
            'aktivitas.required' => 'Aktivitas harus diisi.',
            'koordinat.required' => 'Izinkan website mengakses GPS.',
            'alamat.required' => 'Izinkan website mengakses GPS.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        }

        $img = $request->foto;
        $folderPath = "private/";

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'aktivitas/' . uniqid() . '.png';

        $file = $folderPath . $fileName;
        Storage::put($file, $image_base64);

        $absen =  Absen::where('user_id', $id)->whereDate('created_at', date('Y-m-d'))->first();

        // cek apakah bukan absen pertama
        if (!$absen) {
            $absen = new Absen;
            $absen->user_id = $id;
        } else {
            if (Aktivitas::where('absen_id', $absen->id)->count() > 2) {
                $absen->status = Carbon::parse($absen->created_at)->format('H') > Absen::$late ? 2 : 1;
            }
        }
        $absen->save();

        $data = [
            'absen_id' => $absen->id,
            'user_id' => auth()->user()->id,
            'foto' => $fileName,
            'koordinat' => $request->koordinat,
            'alamat' => $request->alamat,
            'aktivitas' => $request->aktivitas,
        ];

        Aktivitas::create($data);
        return response()->json([
            'status' => 200,
            'message' => 'Absen berhasil',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $absen = Absen::select('id', 'created_at', 'user_id')
            ->with(
                'aktivitass:absen_id,aktivitas,koordinat,alamat,foto,created_at',
                'user:id,nama,foto_profil'
            )
            ->find($id);
        $absen->tanggalFormat = Carbon::parse($absen->created_at)->translatedFormat("l, j F Y");
        $absen->aktivitass->map(function ($aktivitas) {
            $aktivitas->time = Carbon::parse($aktivitas->created_at)->format('H:i');
            return $aktivitas;
        });
        return response()->json($absen);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
