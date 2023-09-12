<?php

namespace App\Http\Controllers\Api;

use App\Models\Tim;
use App\Models\User;
use App\Models\TimAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Other;

class TimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Tim::with('pekerjaan')->select(
            'tims.*',
            'users.nama',
            'users.foto_profil',
            'users.speciality',
            'users.wilayah_id',
            'nama_wilayah',
        )
            ->join('users', 'tims.user_id', '=', 'users.id')
            ->join('wilayahs', 'users.wilayah_id', '=', 'wilayahs.id')
            ->orderBy('created_at', 'desc');

        if ($request->has('tanggal') && $request->tanggal != '') {
            $tanggal = $request->tanggal;
            $query->where('tims.created_at', 'LIKE', '%' . $tanggal . '%');
        }

        if ($request->has('wilayah') && $request->wilayah != '') {
            $wilayah = $request->wilayah;
            $query->where('users.wilayah_id', $wilayah);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = '%' . $request->search . '%';
            $query->whereIn('tims.id', TimAnggota::select('tim_id')
            ->join('users', 'user_id', '=', 'users.id')
            ->where('users.nama', 'LIKE', $search)
            ->groupBy('tim_id')
            ->pluck('tim_id'));
        }

        $tims = $query->get();
        $tims->map(function ($tim) {
            $tim->created_atFormat = Carbon::parse($tim->created_at)->format('H:i | d/m/y');
            $tim->status = $tim->getStatus();
            $anggotaQuery = TimAnggota::select(
                'nama',
                'foto_profil',
                'speciality'
            )
                ->join('users', 'user_id', '=', 'users.id')
                ->where('tim_id', $tim->id);
            $tim->search = implode(' ', $anggotaQuery->pluck('nama')->toArray());
            $tim->anggota = $anggotaQuery->whereNot('user_id', $tim->user_id)->get();
            if ($tim->pekerjaan != []) {
                $tim->pekerjaan->map(function ($pekerjaan) {
                    if ($pekerjaan->pemasangan_id != null) {
                        $pekerjaan->nama = 'Pemasangan';
                    }
                    if ($pekerjaan->laporan_id != null) {
                        $pekerjaan->nama = 'Perbaikan';
                    }
                    if ($pekerjaan->other_id != null) {
                        $pekerjaan->nama = Other::find($pekerjaan->other_id)->nama_pekerjaan;
                    }
                });
            }
            $tim->pekerjaans = $tim->pekerjaan->pluck('nama');
            return $tim;
        });

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
        $anggota = TimAnggota::select('user_id')
            ->whereDate('created_at', date('Y-m-d'));

        if ($request->has('included') && !empty($request->included)) {
            $includedArray = json_decode($request->included);

            if ($includedArray === null) {
                parse_str($request->included, $parsedArray);
                $includedArray = array_map('intval', $parsedArray['included']);
            }
            $anggota->whereNotIn('user_id', $includedArray);
        }

        $teknisis = User::where('role', 2)
            ->whereNotIn('id', $anggota->pluck('user_id'));

        if ($request->has('wilayah') && !empty($request->wilayah)) {
            $teknisis->where('wilayah_id', $request->wilayah);
        }

        if ($request->has('nama') && !empty($request->nama)) {
            $teknisis->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        if ($request->has('teknisis') && !empty($request->teknisis)) {
            $teknisisArray = json_decode($request->teknisis);

            if ($teknisisArray === null) {
                parse_str($request->teknisis, $parsedArray);
                $teknisisArray = array_map('intval', $parsedArray['teknisis']);
            }
            $teknisis->whereNotIn('id', $teknisisArray);
        }

        $teknisiData = $teknisis->get();
        $results = [];
        foreach ($teknisiData as $i => $teknisi) {

            $results[$i] = [
                "id" => $teknisi->id,
                "text" => $teknisi->nama,
                "speciality" => $teknisi->speciality,
                "foto_profil" => $teknisi->foto_profil,
            ];
        }

        $data = [
            "results" => $results,
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
        $tim->status = 1;
        $teknisis = $request->teknisis;
        $tim->save();
        foreach ($teknisis as $teknisi) {
            TimAnggota::create([
                'tim_id' => $tim->id,
                'user_id' => $teknisi
            ]);
        }
        return response([
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
        try {
            $tim = Tim::select(
                'user_id',
                'nama',
                'speciality',
                'foto_profil',
            )
                ->join('users', 'user_id', '=', 'users.id')
                ->find($id);
            $tim->anggota = TimAnggota::select(
                'user_id',
                'nama',
                'speciality',
                'foto_profil',
            )
                ->leftJoin('users', 'user_id', '=', 'users.id')
                ->where('tim_id', $id)
                ->whereNot('user_id', $tim->user_id)
                ->get();
            return response($tim);
        } catch (\Throwable $th) {
            return response(['message' => 'Error while fetching data'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tim $tim)
    {
        try {
            $tim->user_id = $request->ketua_id;
            $tim->save();
            TimAnggota::where('tim_id', $tim->id)->delete();
            $teknisis = $request->teknisis;
            foreach ($teknisis as $teknisi) {
                TimAnggota::create([
                    'tim_id' => $tim->id,
                    'user_id' => $teknisi
                ]);
            }
            return response([
                'message' => 'Tim ' . $tim->id . ' berhasil diubah',
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Tim ' . $tim->id . ' gagal diubah',
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tim $tim)
    {
        try {
            $tim->delete();
            return response([
                'message' => 'Tim ' . $tim->id . ' berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Gagal dalam menghapus data'
            ], 400);
        }
    }
}
