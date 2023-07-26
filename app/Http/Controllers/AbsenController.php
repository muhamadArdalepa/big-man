<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absen;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userData = User::with('absens','absen_details')->where('role',2);
        if ($request->has('kota') && !empty($kota)) {
            $userData->where('kota',$request->kota);
        }
        $users = $userData->get();
        $data = [];
        foreach ($users as $i => $user) {
            $absens = [];
            foreach ($user->absens as $j => $absen) {
                $absen_details = [];
                foreach ($user->absen_details as $key => $value) {
                    if ($value->absen_id == $absen->id) {
                        $absen_details[$key] = [
                            "waktu" => $value->waktu,
                            "foto" => $value->foto,
                            "ket" => $value->ket,
                        ];
                    }
                   
                }
                $absens[$j] = [
                    "id" => $absen->id,
                    "tanggal" => $absen->tanggal,
                    "absen_details" => $absen_details
                ];
            }
            $data[$i] = [
                "id" => $user->id,
                "nama" => $user->nama,
                "absens" => $absens,
            ];
        }
        return response()->json($data);
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
        //
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
