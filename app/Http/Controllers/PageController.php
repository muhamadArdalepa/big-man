<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use App\Models\Kota;
use App\Models\User;
use App\Models\Absen;
use App\Models\Anggota;
use Illuminate\Http\Request;
use App\Models\JenisGangguan;
use Illuminate\Support\Carbon;

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
        $jenis_gangguans = JenisGangguan::select('id', 'jenis_gangguan')->get();
        $this->addBreadcrumb('laporan', route('laporan'));
        return view('pages.admin.laporan', compact('kotas', 'date', 'jenis_gangguans'));
    }
    public function laporan_show($id)
    {
        $this->addBreadcrumb('Laporan', route('laporan'));
        $this->addBreadcrumb('Laporan ' . $id, route('laporan.show', $id));

        return view('pages.admin.laporan_show');
    }
    public function tim()
    {
        $date = date('Y-m-d');
        $kotas = Kota::all();
        $this->addBreadcrumb('tim', route('tim'));
        return view('pages.admin.tim', compact('kotas', 'date'));
    }
    public function absen()
    {
        $kotas = Kota::all();
        $date = date('Y-m-d');
        $id = auth()->user()->id;

        $this->addBreadcrumb('absen', route('absen'));
        if (auth()->user()->role === 1) {
            return view('pages.admin.absen', compact('kotas', 'date'));
        } else if (auth()->user()->role === 2) {
            $absen = Absen::where('user_id', $id)->whereDate('tanggal', $date)->first();
            $date = Carbon::parse($date)->translatedFormat("l, d F Y");
            $now = Carbon::now();
            $action = false;
            $whichAbsen = 'Pertama';
            if (!$absen) {
                $action = true;
            } else {
                if ($absen->waktu2 == null && $now->greaterThanOrEqualTo(Carbon::today()->setTime(11, 0, 0))) {
                    $whichAbsen = 'Kedua';
                    $action = true;
                }
                if ($absen->waktu3 == null && $now->greaterThanOrEqualTo(Carbon::today()->setTime(13, 0, 0))) {
                    $action = true;
                    $whichAbsen = 'Ketiga';
                }
                if ($absen->waktu4 == null && $now->greaterThanOrEqualTo(Carbon::today()->setTime(16, 0, 0))) {
                    $action = true;
                    $whichAbsen = 'Keempat';
                }
            }
            return view('pages.teknisi.absen', compact('date', 'action','whichAbsen'));
        }
    }
    public function pekerjaan()
    {
        $this->addBreadcrumb('pekerjaan', route('pekerjaan'));
        return view('pages.admin.pekerjaan');
    }

    public function teknisi()
    {
        $this->addBreadcrumb('teknisi', route('teknisi'));
        $kotas = Kota::all();
        return view('pages.admin.teknisi', compact('kotas'));
    }

    public function teknisi_show($id)
    {
        $this->addBreadcrumb('teknisi_show', route('teknisi_show'));
        $teknisi = User::with('kota', 'tims')->findOrFail($id);
        return view('pages.admin.teknisi-show', compact('teknisi'));
    }
    public function pelanggan()
    {
        $this->addBreadcrumb('pelanggan', route('pelanggan'));
        $kotas = Kota::all();
        return view('pages.admin.pelanggan', compact('kotas'));
    }

    public function profile()
    {
        $this->addBreadcrumb('profile', route('profile'));
        return view('pages.user-profile');
    }
}
