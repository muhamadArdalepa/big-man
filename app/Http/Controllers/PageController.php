<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use App\Models\wilayah;
use App\Models\User;
use App\Models\Absen;
use App\Models\TimAnggota;
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
        $wilayahs = Wilayah::all();
        $date = date('Y-m-d');
        $jenis_gangguans = JenisGangguan::select('id', 'nama_gangguan')->get();
        $this->addBreadcrumb('laporan', route('laporan'));
        return view('pages.admin.laporan', compact('wilayahs', 'date', 'jenis_gangguans'));
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
        $wilayahs = Wilayah::all();
        $this->addBreadcrumb('tim', route('tim'));
        return view('pages.admin.tim', compact('wilayahs', 'date'));
    }
    public function absen()
    {
        $wilayahs = Wilayah::all();
        $date = date('Y-m-d');
        $id = auth()->user()->id;

        $this->addBreadcrumb('absen', route('absen'));
        if (auth()->user()->role === 1) {
            return view('pages.admin.absen', compact('wilayahs', 'date'));
        } else if (auth()->user()->role === 2) {
            $absen = Absen::select('created_at')->where('user_id', $id)->orderBy('created_at', 'desc')->first();
            // dd($absen == null);
            $date = Carbon::parse($date)->translatedFormat("l, d F Y");
            $action = false;
            $whichAbsen = null;
            $hour = substr(now(), 11, 2);

            if ($absen == null) {
                $action = true;
                $whichAbsen = 'Pertama';
            } else {
                $lastAbsen = substr(Carbon::parse($absen->created_at)->format('H:i:s'), 0, 2);
                // dd($lastAbsen);
                if (($hour >= 0 && $hour < 1) && ($lastAbsen >= 8 && $lastAbsen < 11)) {
                    $whichAbsen = 'Kedua';
                    $action = true;
                }
                if (($hour >= 13 && $hour < 16) && ($lastAbsen >= 11 && $lastAbsen < 13)) {
                    $whichAbsen = 'Ketiga';
                    $action = true;
                }
                if (($hour >= 16) && ($lastAbsen >= 11 && $lastAbsen < 13)) {
                    $whichAbsen = 'Keempat';
                    $action = true;
                }
            }

            return view('pages.teknisi.absen', compact('date', 'action', 'whichAbsen'));
        }
    }
    public function pekerjaan()
    {
        $this->addBreadcrumb('pekerjaan', route('pekerjaan'));
        $wilayahs = Wilayah::all();
        $date = date('Y-m-d');
        return view('pages.admin.pekerjaan', compact('wilayahs', 'date'));
    }

    public function teknisi()
    {
        $this->addBreadcrumb('teknisi', route('teknisi'));
        $wilayahs = Wilayah::all();
        return view('pages.admin.teknisi', compact('wilayahs'));
    }

    public function teknisi_show($id)
    {
        if (auth()->user()->role != 3) {
            $user = User::with('wilayah')->findOrFail($id);
            $tims = Tim::select('tims.id', 'user_id', 'users.nama as ketua', 'foto_profil', 'status')->join('users', 'user_id', '=', 'users.id')->get();
            foreach ($tims as $i => $tim) {
                $tim->anggota = TimAnggota::select('user_id', 'nama', 'foto_profil')->join('users', 'user_id', '=', 'users.id')->where('tim_id', $tim->id)->get();
            }
            $data = [];
            foreach ($tims as $i => $tim) {
                $isHasId = false;
                foreach ($tim->anggota as $anggota) {
                    if ($anggota->user_id == auth()->user()->id) {
                        $isHasId = true;
                    }
                }
                if ($tim->user_id == auth()->user()->id || $isHasId) {
                    $data[$i] = $tim;
                }
            }
            $this->addBreadcrumb('teknisi', route('teknisi'));
            $this->addBreadcrumb('profile', route('teknisi.show', $id));
            return view('pages.user-profile', with(['user' => $user, 'tims' => $data]));
        }
    }
    public function pelanggan()
    {
        $this->addBreadcrumb('pelanggan', route('pelanggan'));
        $wilayahs = Wilayah::all();
        return view('pages.admin.pelanggan', compact('wilayahs'));
    }

    public function auth_profile()
    {
        $tims = Tim::select('tims.id', 'user_id', 'users.nama as ketua', 'foto_profil', 'status')->join('users', 'user_id', '=', 'users.id')->get();
        foreach ($tims as $i => $tim) {
            $tim->anggota = TimAnggota::select('user_id', 'nama', 'foto_profil')->join('users', 'user_id', '=', 'users.id')->where('tim_id', $tim->id)->get();
        }
        $data = [];
        foreach ($tims as $i => $tim) {
            $isHasId = false;
            foreach ($tim->anggota as $anggota) {
                if ($anggota->user_id == auth()->user()->id) {
                    $isHasId = true;
                }
            }
            if ($tim->user_id == auth()->user()->id || $isHasId) {
                $data[$i] = $tim;
            }
        }
        $this->addBreadcrumb('profile', route('auth.profile'));
        return view('pages.auth-profile', with(['tims' => $data]));
    }
}
