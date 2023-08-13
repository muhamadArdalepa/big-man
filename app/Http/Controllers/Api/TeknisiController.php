<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class TeknisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
        $query = User::with('wilayah:id,nama_wilayah')->select('users.id','users.nama','users.speciality','foto_profil','users.no_telp','users.poin','wilayah_id')->where('role',2);
        if ($request->has('wilayah') && $request->wilayah != '') {
            $query->where('wilayah_id',$request->wilayah);
        }
        return response()->json($query->get());
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
            'no_telp' => 'required|numeric'
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
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $data = [
                'nama' => ucwords(trim($request->nama)),
                'role' => 2,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'wilayah_id' => $request->wilayah_id,
                'no_telp' => $request->no_telp,
                'foto_profil' => 'dummy.png',
            ];
            User::create($data);
            return response()->json([
                'status' => 200,
                'message' => 'Teknisi baru telah ditambahkan',
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
        return response()->json(User::with('wilayah','tims')->findOrFail($id));
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