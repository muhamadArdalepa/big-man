<?php

namespace App\Http\Controllers\Teknisi;

use App\Models\Absen;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Carbon::setLocale('id');
        $id = auth()->user()->id;
        $absens = Absen::where('user_id', $id)->get();
        foreach ($absens as $absen) {
            $absen->tanggalParsed = Carbon::parse($absen->tanggal)->translatedFormat("l, d F Y");
        }
        return response()->json($absens);
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
        $id = auth()->user()->id;
        $img = $request->image;
        $folderPath = "private/absen/";

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';

        $file = $folderPath . $fileName;
        Storage::put($file, $image_base64);
        $now = Carbon::now();
        $tanggalHariIni = now()->format('Y-m-d');
        $waktuHariIni = now()->format('H:i:s');
        $absenHariIni = Absen::where('user_id', $id)->whereDate('tanggal', $tanggalHariIni)->first();

        $data = [
            'user_id' => $id,
            'tanggal'=> $tanggalHariIni
        ];

        if ($now->hour < 11) {
            $data['foto1'] = $fileName;
            $data['koordinat1'] = $request->koordinat;
            $data['alamat1'] = $request->alamat;
            $data['ket1'] = $request->ket;
            $data['waktu1'] = $waktuHariIni;
        } elseif ($now->hour < 13) {
            $data['foto2'] = $fileName;
            $data['koordinat2'] = $request->koordinat;
            $data['alamat2'] = $request->alamat;
            $data['ket2'] = $request->ket;
            $data['waktu2'] = $waktuHariIni;
        } elseif ($now->hour < 16) {
            $data['foto3'] = $fileName;
            $data['koordinat3'] = $request->koordinat;
            $data['alamat3'] = $request->alamat;
            $data['ket3'] = $request->ket;
            $data['waktu3'] = $waktuHariIni;
        } else {
            $data['foto4'] = $fileName;
            $data['koordinat4'] = $request->koordinat;
            $data['alamat4'] = $request->alamat;
            $data['ket4'] = $request->ket;
            $data['waktu4'] = $waktuHariIni;
        }
        
        if (!$absenHariIni) {
            Absen::create($data);
            return response()->json([
                'status' => 200,
                'message' => 'Absen berhasil',
            ]);
        } else {
            if ($absenHariIni->waktu2 == null) {
                $absenHariIni->foto2 = $fileName;
                $absenHariIni->koordinat2 = $request->koordinat;
                $absenHariIni->alamat2 = $request->alamat;
                $absenHariIni->ket2 = $request->ket;
                $absenHariIni->waktu2 = $waktuHariIni;
                $absenHariIni->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Absen berhasil',
                ]);
            }
            if ($absenHariIni->waktu3 == null) {
                $absenHariIni->foto3 = $fileName;
                $absenHariIni->koordinat3 = $request->koordinat;
                $absenHariIni->alamat3 = $request->alamat;
                $absenHariIni->ket3 = $request->ket;
                $absenHariIni->waktu3 = $waktuHariIni;
                $absenHariIni->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Absen berhasil',
                ]);
            }
            if ($absenHariIni->waktu4 == null) {
                $absenHariIni->foto4 = $fileName;
                $absenHariIni->koordinat4 = $request->koordinat;
                $absenHariIni->alamat4 = $request->alamat;
                $absenHariIni->ket4 = $request->ket;
                $absenHariIni->waktu4 = $waktuHariIni;
                $absenHariIni->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Absen berhasil',
                ]);
            }
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
        $absen = Absen::find($id);
        $absen->tanggalFormat =  Carbon::parse($absen->tanggal)->translatedFormat("l, d F Y");
        return $absen;
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
