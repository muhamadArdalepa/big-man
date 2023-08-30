<?php

namespace App\Http\Controllers\Api;

use App\Models\Pemasangan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PelangganPemasanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pemasangan = Pemasangan::where('pelanggan', auth()->user()->id)->get();
        return response()->json($pemasangan);
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
            'nik' => 'required|numeric',
            'paket_id' => 'required',
            'alamat' => 'required',
            'koordinat_rumah' => 'required',
            'foto_ktp' =>  'required|image',
            'foto_rumah' =>  'required|image'
        ]);

        $validatedData['pelanggan'] = auth()->user()->id;
        $validatedData['status'] = 'menunggu konfirmasi';

        if ($request->hasFile('foto_ktp')) {
            $fotoName = uniqid() . '.' . $request->file('foto_ktp')->getClientOriginalExtension();
            $compressedImage = Image::make($request->file('foto_ktp')->getRealPath());

            $compressedImage->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $folderPath = 'private/pemasangan/';
            $compressedImagePath = $folderPath . $fotoName;
            Storage::put($compressedImagePath, (string) $compressedImage->encode());
            $validatedData['foto_ktp'] = $fotoName;
        }

        if ($request->hasFile('foto_rumah')) {
            $fotoName = uniqid() . '.' . $request->file('foto_rumah')->getClientOriginalExtension();
            $compressedImage = Image::make($request->file('foto_rumah')->getRealPath());

            $compressedImage->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $folderPath = 'private/pemasangan/';
            $compressedImagePath = $folderPath . $fotoName;
            Storage::put($compressedImagePath, (string) $compressedImage->encode());
            $validatedData['foto_rumah'] = $fotoName;
        }

        if (Pemasangan::create($validatedData)) {
            return response()->json(
                ['message' => 'Aktivitas baru berhasil ditambahkan']
            );
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
