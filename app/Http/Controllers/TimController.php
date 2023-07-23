<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use App\Models\User;
use App\Models\TimAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TimController extends Controller
{
    public function teknisi()
    {
        $data = User::with('tims')->where('role', 2)->orderBy('nama', 'asc')->get();
        return response()->json($data);
    }
    public function index(Request $request)
    {
        $tims = null;
        if ($request->has('kota') && $request->kota != '') {
            $kota = $request->kota;
            $tims = Tim::with('user')
                ->whereNull('deleted_at')
                ->whereHas('user', function ($query) use ($kota) {
                    $query->where('kota_id', $kota);
                })->get();
        } else {
            $tims = Tim::with('user')
                ->whereNull('deleted_at')
                ->get();
        }

        $data = [];

        foreach ($tims as $i => $tim) {
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
                "status" => $tim->user->status,
                "kota" => $tim->user->kota->kota,
                "foto_profil" => $tim->user->foto_profil,
                "timestamp" => Carbon::parse($tim->user->created_at)->format('H:i | d/m/y'),
                "anggota" => $data2
            ];
        }

        return $data;
    }
}
