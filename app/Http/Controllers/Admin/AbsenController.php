<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::select('absens.id', 'nama', 'foto_profil', 'waktu1', 'waktu2', 'waktu3', 'waktu4', 'tanggal')
            ->leftJoin('absens', 'users.id', '=', 'absens.user_id')
            ->where('role', 2);

        if ($request->has('kota') && !empty($request->kota)) {
            $users->where('kota_id', $request->kota);
        }

        $tanggal = $request->tanggal;
        $users->where(function ($query) use ($tanggal) {
            $query->where('tanggal', $tanggal)
                ->orWhereNull('tanggal');
        });

        $data = $users->orderBy('absens.updated_at', 'desc')->get();
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
        Carbon::setLocale('id');
        $absen = Absen::with('user')->find($id);
        $absen->tanggal = Carbon::parse($absen->tanggal)->translatedFormat("l, d F Y");
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
