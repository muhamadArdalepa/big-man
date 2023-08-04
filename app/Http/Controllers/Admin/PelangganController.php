<?php

namespace App\Http\Controllers\Admin;

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
            return response()->json(User::with('wilayah', 'pemasangan')->where('role', 3)->where('wilayah_id', $request->wilayah)->get());
        } else {
            return response()->json(User::with('wilayah', 'pemasangan')->where('role', 3)->get());
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
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'wilayah_id' => 'required',
            'no_telp' => 'required|numeric|digits_between:11,15'
        ], [
            'nama.required' => 'Nama harus diisi.',
            'nama.min' => 'Nama harus memiliki minimal 3 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password harus memiliki minimal 6 karakter.',
            'wilayah_id.required' => 'wilayah harus dipilih.',
            'no_telp.required' => 'Nomor telepon harus diisi.',
            'no_telp.numeric' => 'Nomor telepon harus berupa angka.',
            'no_telp.digits_between' => 'Nomor telepon memiliki minimal 11 karakter.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $pelanggaan = new User;
            $pelanggaan->nama = ucwords(trim($request->nama));
            $pelanggaan->role = 3;
            $pelanggaan->email = $request->email;
            $pelanggaan->password = $request->password;
            $pelanggaan->wilayah_id = $request->wilayah_id;
            $pelanggaan->no_telp = $request->no_telp;
            $pelanggaan->foto_profil = 'dummy.png';
            $pelanggaan->save();
            $pemasangan = new Pemasangan;
            $pemasangan->user_id = $pelanggaan->id;
            $pemasangan->alamat = $request->alamat;
            $pemasangan->save();
            return response()->json([
                'status' => 200,
                'message' => 'Pelanggan baru telah ditambahkan',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(User::with('wilayah','pemasangan')->findOrFail($id));
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