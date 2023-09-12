<?php

namespace App\Http\Controllers\Api;

use App\Models\Pemasangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index(Request $request)
    {
        if ($request->has('wilayah') && $request->wilayah != '') {
            return response()->json(User::with('wilayah')->where('role', 3)->where('wilayah_id', $request->wilayah)->get());
        } else {
            return response()->json(User::with('wilayah')->where('role', 3)->get());
        }
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
        $validatdData = $request->validate([
            'nama' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'wilayah_id' => 'required',
            'no_telp' => 'required|numeric|digits_between:11,15'
        ]);
        $pelanggaan = new User;
        $pelanggaan->nama = ucwords(trim($request->nama));
        $pelanggaan->role = 3;
        $pelanggaan->email = $request->email;
        $pelanggaan->password = $request->password;
        $pelanggaan->wilayah_id = $request->wilayah_id;
        $pelanggaan->no_telp = $request->no_telp;
        $pelanggaan->foto_profil = 'profile/dummy.png';
        $pelanggaan->save();
        return response()->json([
            'status' => 200,
            'message' => 'Pelanggan baru telah ditambahkan',
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
        return response()->json(User::with('wilayah', 'pemasangan')->findOrFail($id));
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
        if (!User::destroy($id)) {
            return response()->json([
                'status' => 400,
                'errors' => 'Failed Deleted'
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Teknisi telah dihapus',
        ]);
    }
}
