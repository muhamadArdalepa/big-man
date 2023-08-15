<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Aktivitas;

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

    public function index(Request $request)
    {

        $query = User::select(
            'id',
            'nama',
            'foto_profil'
        )
            ->where('role', 2)
            ->orderBy('nama', 'asc');

        if ($request->has('wilayah') && !empty($request->wilayah)) {
            $query->where('wilayah_id', $request->wilayah);
        }

        $tanggal = '%' . date('Y-m-d') . '%';
        if ($request->has('tanggal') && !empty($request->tanggal)) {
            $tanggal = '%' . $request->tanggal . '%';
        }

        $users = $query->get();
        foreach ($users as $i => $user) {
            $absens = Absen::where('created_at', 'LIKE', $tanggal)
                ->where('user_id', '=', $user->id)
                ->first();
            $user->absen_id = null;
            for ($j = 0; $j < 4; $j++) {
                $user->absens[$j] = null;
            }

            if ($absens) {
                $user->absen_id = $absens->id;
                $aktivitas = Aktivitas::select('created_at')->where('absen_id', $absens->id)->get();
                if ($aktivitas->count() < 4) {
                    for ($j = 0; $j < 4; $j++) {
                        $user->absens[$j] = null;
                    }
                    for ($j = 0; $j < $aktivitas->count(); $j++) {
                        $hour = (int) substr($aktivitas[$j]->created_at, 11, 2);
                        $timeFormat = Carbon::createFromFormat('Y-m-d H:i:s', $aktivitas[$j]->created_at)->format('H:i');
                        if ($hour >= 8 && $hour < 11) {
                            $user->absens[0] = $timeFormat;
                        } else if ($hour >= 11 && $hour < 13) {
                            $user->absens[1] = $timeFormat;
                        } else if ($hour >= 13 && $hour < 16) {
                            $user->absens[2] = $timeFormat;
                        } else if ($hour >= 16) {
                            $user->absens[3] = $timeFormat;
                        }
                    }
                }
            }
        }
        return response()->json($users);
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
