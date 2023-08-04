<?php

namespace App\Http\Controllers\Teknisi;

use App\Models\Absen;
use App\Models\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        Carbon::setLocale('id');
    }

    public function index()
    {
        $id = auth()->user()->id;
        $data = [];

        $absens = Absen::with('aktivitass')->where('user_id', $id)->get();
        foreach ($absens as $i => $absen) {
            $data[$i]['id'] = $absen->id;
            $data[$i]['created_at'] = $absen->created_at;
            $data[$i]['tanggalFormat'] = Carbon::parse($absen->created_at)->translatedFormat("l, d F Y");
            $n = $absen->aktivitass->count();

            $data[$i]['absens'] = [];
            for ($j = 0; $j < 4; $j++) {
            $data[$i]['absens'][$j] = null;
            }
            for ($j = 0; $j < $n; $j++) {
                $hour = (int) substr($absen->aktivitass[$j]->created_at, 11, 2);
                if ($hour >= 8 && $hour < 11) {
                    $data[$i]['absens'][0] = Carbon::createFromFormat('Y-m-d H:i:s', $absen->aktivitass[$j]->created_at)->format('H:i');
                } else if ($hour >= 11 && $hour < 13) {
                    $data[$i]['absens'][1] = Carbon::createFromFormat('Y-m-d H:i:s', $absen->aktivitass[$j]->created_at)->format('H:i');
                } else if ($hour >= 13 && $hour < 16) {
                    $data[$i]['absens'][2] = Carbon::createFromFormat('Y-m-d H:i:s', $absen->aktivitass[$j]->created_at)->format('H:i');
                } else if ($hour >= 16) {
                    $data[$i]['absens'][3] = Carbon::createFromFormat('Y-m-d H:i:s', $absen->aktivitass[$j]->created_at)->format('H:i');
                }
            }
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
        $validator = Validator::make($request->all(), [
            'foto' => 'required',
            'aktivitas' => 'required',
            'koordinat' => 'required',
        ], [
            'foto.required' => 'Foto harus diisi',
            'aktivitas.required' => 'Aktivitas harus diisi.',
            'koordinat.required' => 'Izinkan website mengakses GPS.',
            'alamat.required' => 'Izinkan website mengakses GPS.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        }
        $img = $request->foto;
        $folderPath = "private/absen/";

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';

        $file = $folderPath . $fileName;
        Storage::put($file, $image_base64);

        $absen =  Absen::where('user_id',$id)->whereDate('created_at',date('Y-m-d'))->first() ?? new Absen;

        $absen->user_id = $id;
        $absen->save();

        $data = [
            'absen_id' => $absen->id,
            'foto' => $fileName,
            'koordinat' => $request->koordinat,
            'alamat' => $request->alamat,
            'aktivitas' => $request->aktivitas,
        ];

        Aktivitas::create($data);
        return response()->json([
            'status' => 200,
            'message' => 'Absen berhasil',
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
        $absen = Absen::with('aktivitass','user')->find($id);
        $absen->tanggalFormat =Carbon::parse($absen->created_at)->translatedFormat("l, d F Y");
        return response()->json($absen);
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
