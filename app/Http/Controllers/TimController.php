<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use App\Models\User;
use App\Models\TimAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tims = Tim::with('user')->orderBy('updated_at','desc');
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $tims->whereHas('user', function ($query) use ($search) {
                $query->where('nama', 'LIKE', '%' . $search . '%');
            });
        }
        if ($request->has('tanggal') && $request->tanggal != '') {
            $tanggal = $request->tanggal;
            $tims->where('updated_at', 'LIKE', '%' . $tanggal . '%');
        }
        if ($request->has('kota') && $request->kota != '') {
            $kota = $request->kota;
            $tims->whereHas('user', function ($query) use ($kota) {
                $query->where('kota_id', $kota);
            });
        }
        $data = [];
        foreach ($tims->get() as $i => $tim) {
            $data2 = [];
            $anggotas = TimAnggota::with('user')
                ->where('tim_id', $tim->id)
                ->where('user_id', '!=', $tim->user_id)
                ->get();

            foreach ($anggotas as $j => $anggota) {
                $data2[$j] = [
                    "anggota_id" => $anggota->user->id,
                    "anggota_nama" => $anggota->user->nama,
                    "anggota_foto_profil" => $anggota->user->foto_profil,
                ];
            }
            $data[$i] = [
                "id" => $tim->id,
                "ketua_id" => $tim->user->id,
                "ketua" => $tim->user->nama,
                "status" => $tim->status,
                "kota" => $tim->user->kota->kota,
                "foto_profil" => $tim->user->foto_profil,
                "timestamp" => Carbon::parse($tim->user->created_at)->format('H:i | d/m/y'),
                "anggota" => $data2
            ];
        }

        return response()->json($data);
    }
    public function select2_teknisi(Request $request)
    {
        $results = [];


        $teknisis = User::with('kota', 'tims')->where('role', 2);
        if ($request->has('kota') && !empty($request->kota)) {
            $teknisis->where('kota_id', $request->kota);
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

        foreach ($teknisiData as $i => $teknisi) {

            $results[$i] = [
                "id" => $teknisi->id,
                "text" => $teknisi->nama,
                "foto_profil" => $teknisi->foto_profil,
                "tims" => $teknisi->tims->first()
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
        $tim->status = "Standby";
        $teknisis = $request->teknisis;
        $tim->save();
        foreach ($teknisis as $teknisi) {
            TimAnggota::create([
                'tim_id' =>$tim->id,
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
        //
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
