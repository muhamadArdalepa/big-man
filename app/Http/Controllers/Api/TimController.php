<?php

namespace App\Http\Controllers\Api;

use App\Models\Tim;
use App\Models\User;
use App\Models\TimAnggota;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $query = Tim::select(
            'tims.id',
            'tims.status',
            'tims.created_at',
            'tims.user_id',
            'users.nama',
            'users.foto_profil',
            'users.speciality',
            'users.wilayah_id',
            'nama_wilayah',
            'jenis_pekerjaans.nama_pekerjaan',
        )
            ->join('users', 'tims.user_id', '=', 'users.id')
            ->join('wilayahs', 'users.wilayah_id', '=', 'wilayahs.id')
            ->leftJoin('pekerjaans', 'tims.id', '=', 'pekerjaans.tim_id')
            ->leftJoin('jenis_pekerjaans', 'jenis_pekerjaan_id', '=', 'jenis_pekerjaans.id');

        if ($request->has('tanggal') && $request->tanggal != '') {
            $tanggal = $request->tanggal;
            $query->where('tims.created_at', 'LIKE', '%' . $tanggal . '%');
        }
        if ($request->has('wilayah') && $request->wilayah != '') {
            $wilayah = $request->wilayah;
            $query->where('users.wilayah_id', $wilayah);
        }

        $tims = $query->get();
        foreach ($tims as $tim) {
            $tim->created_atFormat = Carbon::parse($tim->created_at)->format('H:i | d/m/y');
            $tim->search = '';
            foreach (TimAnggota::select('users.nama')->join('users', 'user_id', '=', 'users.id')->get() as $temp) {
                $tim->search .= $temp->nama . ' ';
            }
            $tim->anggota = TimAnggota::select(
                'nama',
                'foto_profil',
                'speciality',
            )
                ->join('users', 'user_id', '=', 'users.id')
                ->where('tim_id', $tim->id)
                ->whereNot('user_id', $tim->user_id)->get();
        }

        if ($request->has('search') && $request->search != '') {
            $search = '%' . $request->search . '%';
            $timf = [];
            foreach (TimAnggota::select('tim_id')
                ->join('users', 'user_id', '=', 'users.id')
                ->where('users.nama', 'LIKE', $search)
                ->groupBy('tim_id')
                ->get('tim_id') as $id) {
                array_push($timf, $id->tim_id);
            }
            $tims = $tims->whereIn('id', $timf);
        }

        return response()->json($tims);
    }
    public function select2_teknisi(Request $request)
    {
        $query = User::select(
            'id',
            'nama',
            'foto_profil',
            'speciality',
        )
            ->where('role', 2)
            ->whereNotIn('id', TimAnggota::select('user_id')
                ->whereDate('created_at', '=', Carbon::today())
                ->groupBy('user_id')
                ->get('user_id'));

        if ($request->has('wilayah') && !empty($request->wilayah)) {
            $query->where('wilayah_id', $request->wilayah);
        }

        if ($request->has('nama') && !empty($request->nama)) {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        if ($request->has('teknisis') && !empty($request->teknisis)) {
            $teknisisArray = json_decode($request->teknisis);

            if ($teknisisArray === null) {
                parse_str($request->teknisis, $parsedArray);
                $teknisisArray = array_map('intval', $parsedArray['teknisis']);
            }
            $query->whereNotIn('id', $teknisisArray);
        }

        $teknisis = $query->get();

        foreach ($teknisis as  $teknisi) {
            $teknisi->text = $teknisi->nama;
        }

        $data = [
            "results" => $teknisis,
            "pagination" => [
                "more" => false
            ]
        ];

        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tim = new Tim;
        $tim->user_id = $request->ketua_id;
        $tim->status = "Standby";
        $teknisis = $request->teknisis;
        $tim->save();
        foreach ($teknisis as $teknisi) {
            TimAnggota::create([
                'tim_id' => $tim->id,
                'user_id' => $teknisi
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Tim baru telah ditambahkan',
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
        $tim = Tim::select(
            'tims.id',
            'tims.status',
            'tims.created_at',
            'tims.user_id',
            'users.nama',
            'users.foto_profil',
            'users.speciality',
            'users.no_telp',
            'users.wilayah_id',
            'nama_wilayah',
            'jenis_pekerjaans.nama_pekerjaan',
        )
            ->join('users', 'tims.user_id', '=', 'users.id')
            ->join('wilayahs', 'users.wilayah_id', '=', 'wilayahs.id')
            ->leftJoin('pekerjaans', 'tims.id', '=', 'pekerjaans.tim_id')
            ->leftJoin('jenis_pekerjaans', 'jenis_pekerjaan_id', '=', 'jenis_pekerjaans.id')->find($id);


        $tim->created_atFormat = Carbon::parse($tim->created_at)->format('H:i | d/m/y');
        $tim->anggota = TimAnggota::select(
            'nama',
            'foto_profil',
            'speciality',
            'no_telp',
        )
            ->join('users', 'user_id', '=', 'users.id')
            ->where('tim_id', $tim->id)
            ->whereNot('user_id', $tim->user_id)->get();



        return response()->json($tim);
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
