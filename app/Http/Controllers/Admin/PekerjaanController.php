<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tim;
use App\Models\Pekerjaan;
use App\Models\TimAnggota;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pekerjaans = Pekerjaan::all();
        $data = [];

        foreach($pekerjaans as $i => $pekerjaan) {
            $pekerjaan->tim = Tim::select('users.nama')->join('users','users.id','=','user_id')->find($pekerjaan->tim_id);
            // $pekerjaan   
            $pekerjaan->tim->anggota = TimAnggota::select('users.nama')->join('users','users.id','=','user_id')->where('tim_id',$pekerjaan->tim_id)->get();
        }
        return response()->json($pekerjaans);
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
