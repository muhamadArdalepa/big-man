<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use App\Models\Kota;
use App\Models\User;
use App\Models\Anggota;
use App\Models\JenisGangguan;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.dashboard');
    }
    public function laporan()
    {
        $kotas = Kota::all();
        $date = date('Y-m-d');
        $jenis_gangguans = JenisGangguan::select('id','jenis_gangguan')->get();
        $this->addBreadcrumb('laporan',route('laporan'));
        return view('pages.laporan',compact('kotas','date','jenis_gangguans'));
    }
    public function laporan_show($id){
        $this->addBreadcrumb('Laporan', route('laporan'));
        $this->addBreadcrumb('Laporan '.$id, route('laporan.show',$id));
    
        return view('pages.laporan_show');
    }
    public function tim()
    {
        $date = date('Y-m-d');
        $kotas = Kota::all();
        $this->addBreadcrumb('tim',route('tim'));
        return view('pages.tim',compact('kotas','date'));
    }
    public function absen()
    {
        $kotas = Kota::all();
        $date = date('Y-m-d');
        $this->addBreadcrumb('absen',route('absen'));
        return view('pages.absen',compact('kotas','date'));
    }
    public function pekerjaan()
    {
        $this->addBreadcrumb('pekerjaan',route('pekerjaan'));
        return view('pages.pekerjaan');
    }

    public function teknisi()
    {
        $this->addBreadcrumb('teknisi',route('teknisi'));
        $kotas = Kota::all();
        return view('pages.teknisi',compact('kotas'));
    }
   
    public function teknisi_show($id)
    {
        $this->addBreadcrumb('teknisi_show',route('teknisi_show'));
        $teknisi = User::with('kota','tims')->findOrFail($id);
        return view('pages.teknisi-show',compact('teknisi'));
    }
    public function pelanggan()
    {
        $this->addBreadcrumb('pelanggan',route('pelanggan'));
        $kotas = Kota::all();
        return view('pages.pelanggan',compact('kotas'));
    }

    public function profile()
    {
        $this->addBreadcrumb('profile',route('profile'));
        return view('pages.user-profile');
    }
}
