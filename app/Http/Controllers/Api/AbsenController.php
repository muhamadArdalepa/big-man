<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Aktivitas;

use function PHPUnit\Framework\greaterThanOrEqual;

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
        $data = [];
        $tanggal = '%' . $request->tanggal . '%';

        $users = User::select('id', 'nama', 'foto_profil')->where('role', 2)->orderBy('nama', 'asc');
        if ($request->has('wilayah') && !empty($request->wilayah)) {
            $users->where('wilayah_id', $request->wilayah);
        }

        foreach ($users->get() as $i => $user) {

            $absens = Absen::where('created_at', 'LIKE', $tanggal)
                ->where('user_id', '=', $user->id)->first();
            $data[$i]['nama'] = $user->nama;
            $data[$i]['foto_profil'] = $user->foto_profil;
            $data[$i]['absen_id'] = null;
            $data[$i]['absens'] = [];

            if ($absens) {
                $data[$i]['absen_id'] = $absens->id;
                $n = Aktivitas::select('created_at')->where('absen_id', $absens->id)->get();
                if ($n->count() < 4) {
                    for ($j = 0; $j < 4; $j++) {
                        $data[$i]['absens'][$j] = null;
                    }
                    for ($j = 0; $j < $n->count(); $j++) {
                        $hour = (int) substr($n[$j]->created_at, 11, 2);
                        if ($hour >= 8 && $hour < 11) {
                            $data[$i]['absens'][0] = Carbon::createFromFormat('Y-m-d H:i:s', $n[$j]->created_at)->format('H:i');
                        } else if ($hour >= 11 && $hour < 13) {
                            $data[$i]['absens'][1] = Carbon::createFromFormat('Y-m-d H:i:s', $n[$j]->created_at)->format('H:i');
                        } else if ($hour >= 13 && $hour < 16) {
                            $data[$i]['absens'][2] = Carbon::createFromFormat('Y-m-d H:i:s', $n[$j]->created_at)->format('H:i');
                        } else if ($hour >= 16) {
                            $data[$i]['absens'][3] = Carbon::createFromFormat('Y-m-d H:i:s', $n[$j]->created_at)->format('H:i');
                        }
                    }
                }
            } else {
                for ($j = 0; $j < 4; $j++) {
                    $data[$i]['absens'][$j] = null;
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
        // $absens = Aktivitas::with('user')->select('created_at as waktu', 'foto', 'aktivitas', 'koordinat', 'alamat')->find($id)->get();
        $absen = Absen::with('user','aktivitass')->find($id);
        $absen->tanggalFormat = Carbon::parse($absen->created_at)->translatedFormat("l, d F Y");
        
        // $data = [
        //     'tanggalFormat' => Carbon::parse($absens[0]->waktu)->translatedFormat("l, d F Y"),
        //     'nama' => $user->nama,
        //     'foto_profil' => $user->foto_profil,
        //     'absens' => $absens
        // ];
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
