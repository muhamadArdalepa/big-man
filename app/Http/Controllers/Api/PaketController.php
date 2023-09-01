<?php

namespace App\Http\Controllers\Api;

use App\Models\Paket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Paket::latest()->get()->map(function ($paket) {
            return [
                'id' => $paket->id,
                'nama_paket' => $paket->nama_paket,
                'kecepatan' => $paket->kecepatan . ' Mbps',
                'harga' => $paket->formatted_harga,
                'ket' => $paket->ket,
            ];
        }));
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
        $validatedData = $request->validate([
            'nama_paket' => 'required',
            'kecepatan' => 'required',
            'harga' => 'required',
            'ket' => 'nullable',
        ]);
        Paket::create($validatedData);
        return response()->json([
            'status' => 200,
            'message' => 'Paket baru telah ditambahkan',
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
        return response()->json(Paket::findOrFail($id));
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
        $validatedData = $request->validate([
            'nama_paket' => 'required',
            'kecepatan' => 'required',
            'harga' => 'required',
            'ket' => 'nullable',
        ]);
        Paket::find($id)->update($validatedData);
        return response()->json([
            'status' => 200,
            'message' => 'Paket berhasil diubah',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $wilayah = Paket::findOrFail($id);
            $wilayah->delete();

            return response()->json([
                'message' => 'Data paket berhasil dihapus.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Paket ini tidak bisa dihapus',
            ], 500);
        }
    }
}
