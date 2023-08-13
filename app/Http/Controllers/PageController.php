<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use App\Models\User;
use App\Models\Absen;
use App\Models\Paket;
use App\Models\Laporan;
use App\Models\wilayah;
use App\Models\Pekerjaan;
use App\Models\Pemasangan;
use App\Models\TimAnggota;
use App\Models\JenisGangguan;
use App\Models\JenisPekerjaan;
use Illuminate\Support\Carbon;

class PageController extends Controller
{
    public function home()
    {
        switch (auth()->user()->role) {
            case 1:
                return view('pages.admin.dashboard');
                break;
            case 2:
                return view('pages.teknisi.dashboard');
                break;
            case 3:
                return view('pages.pelanggan.dashboard');
                break;
        }
    }
    public function pekerjaan()
    {
        $this->addBreadcrumb('pekerjaan', route('pekerjaan'));

        $wilayahs = Wilayah::all();
        $jenis_pekerjaans = JenisPekerjaan::all();
        $date = date('Y-m-d');
        switch (auth()->user()->role) {
            case 1:
                return view('pages.admin.pekerjaan', compact('wilayahs', 'jenis_pekerjaans', 'date'));
                break;
            case 2:
                $pekerjaan = Pekerjaan::select(
                    'pekerjaans.id',
                    'pekerjaans.tim_id',
                    'jenis_pekerjaan_id',
                    'jenis_pekerjaans.nama_pekerjaan',
                    'pemasangan_id',
                    'laporan_id',
                    'detail',
                    'pekerjaans.poin',
                    'pekerjaans.status',
                    'pekerjaans.created_at'
                )
                    ->join('tims', 'pekerjaans.tim_id', '=', 'tims.id')
                    ->join('jenis_pekerjaans', 'jenis_pekerjaan_id', '=', 'jenis_pekerjaans.id')
                    ->join('tim_anggotas', 'tims.id', '=', 'tim_anggotas.tim_id')
                    ->where('tim_anggotas.user_id', auth()->user()->id)
                    ->where('tim_anggotas.user_id', auth()->user()->id)
                    ->latest()->first();

                switch ($pekerjaan->jenis_pekerjaan_id) {
                    case 1:
                        $pemasangan = Pemasangan::select('users.nama', 'alamat')->join('users', 'pelanggan', '=', 'users.id')->find($pekerjaan->pemasangan_id);
                        $pekerjaan->detail = $pemasangan->nama . ' - ' . $pemasangan->alamat;
                        break;
                    case 2:
                        $laporan = Laporan::select('users.nama', 'pemasangans.alamat')->join('users', 'pelapor', '=', 'users.id')
                            ->join('pemasangans', 'user_id', '=', 'pemasangans.user_id')->find($pekerjaan->laporan_id);
                        $pekerjaan->detail = $laporan->nama . ' - ' . $laporan->alamat;
                        break;
                    default:
                        $pekerjaan->detail = $pekerjaan->detail;
                        break;
                };
                $pekerjaan->created_atFormat = Carbon::parse($pekerjaan->created_at)->translatedFormat('H:i');
                $pekerjaan->updated_atFormat = Carbon::parse($pekerjaan->updated_at)->translatedFormat('H:i');
                return view('pages.teknisi.pekerjaan', compact('jenis_pekerjaans', 'pekerjaan'));
                break;
        }
    }

    public function pekerjaan_show($id)
    {
        $this->addBreadcrumb('Pekerjaan', route('pekerjaan'));
        $this->addBreadcrumb('Pekerjaan ' . $id, route('pekerjaan.show', $id));
        $pekerjaan = Pekerjaan::with('jenis_pekerjaan')->findOrFail($id);
        switch ($pekerjaan->jenis_pekerjaan_id) {
            case 1:
                $detail = Pemasangan::select(
                    'pemasangans.*',
                    'users.nama',
                    'users.no_telp',
                    'users.email',
                    'users.foto_profil',
                    'marketer.nama as marketer'
                )
                    ->leftJoin('users', 'pelanggan', '=', 'users.id')
                    ->leftJoin('users as marketer', 'marketer', '=', 'marketer.id')
                    ->findOrFail($pekerjaan->pemasangan_id);
                break;
            default:
                # code...
                break;
        }
        // dd($detail);
        return view('pages.pekerjaan-show', compact('pekerjaan', 'detail'));
    }
    public function pemasangan()
    {
        switch (auth()->user()->role) {
            case 1:
                $this->addBreadcrumb('pemasangan', route('pemasangan'));
                $wilayahs = Wilayah::all();
                $date = date('Y-m-d');
                $pakets = Paket::all();

                return view('pages.admin.pemasangan', compact('wilayahs', 'date', 'pakets'));
            case 2:
                $this->addBreadcrumb('pemasangan', route('pemasangan'));
                return view('pages.teknisi.pemasangan');
            case 3:
                $pemasangan = Pemasangan::where('pelanggan', auth()->user()->id)->first();
                $this->addBreadcrumb($pemasangan ? 'Daftar Jaringan' : 'Detail Pemasangan', route('pemasangan'));
                if ($pemasangan) {
                    $detail = Pemasangan::select(
                        'pemasangans.id',
                        'pemasangans.status',
                        'pemasangans.nik',
                        'pemasangans.alamat',
                        'pemasangans.koordinat_rumah',
                        'pemasangans.koordinat_odp',
                        'pemasangans.serial_number',
                        'pemasangans.ssid',
                        'pemasangans.password',
                        'pemasangans.hasil_opm_user',
                        'pemasangans.hasil_opm_odp',
                        'pemasangans.kabel_terpakai',
                        'users.nama',
                        'users.no_telp',
                        'users.email',
                        'users.foto_profil',
                        'marketer.nama as marketer'
                    )
                        ->leftJoin('users', 'pelanggan', '=', 'users.id')
                        ->leftJoin('users as marketer', 'marketer', '=', 'marketer.id')
                        ->where('pelanggan',auth()->user()->id)->first();

                    $pekerjaan = Pekerjaan::with('jenis_pekerjaan')->where('pemasangan_id',$pemasangan->id)->first();
                    return view('pages.pekerjaan-show', compact('pekerjaan', 'detail'));
                }
                $pakets = Paket::all();
                return view('pages.pelanggan.pemasangan-form', compact('pemasangan', 'pakets'));
        }
    }
    public function laporan()
    {
        $jenis_gangguans = JenisGangguan::select('id', 'nama_gangguan')->get();
        $this->addBreadcrumb('laporan', route('laporan'));
        switch (auth()->user()->role) {
            case 1:
                $wilayahs = Wilayah::all();
                $date = date('Y-m-d');
                return view('pages.admin.laporan', compact('wilayahs', 'date', 'jenis_gangguans'));
                break;
            case 3:
                return view('pages.pelanggan.laporan', compact('jenis_gangguans'));
                break;
        }
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
        $this->addBreadcrumb('absen', route('absen'));

        $wilayahs = Wilayah::all();
        $date = date('Y-m-d');
        $id = auth()->user()->id;

        if (auth()->user()->role === 1) {
            return view('pages.admin.absen', compact('wilayahs', 'date'));
        }
        if (auth()->user()->role === 2) {
            $absen = Absen::select('created_at')
                ->where('user_id', $id)
                ->whereDate('created_at', date('Y-m-d'))
                ->orderBy('created_at', 'desc')
                ->first();
            $date = Carbon::parse($date)->translatedFormat("l, d F Y");
            $action = false;
            $whichAbsen = null;
            $hour = substr(now(), 11, 2);

            if ($absen == null) {
                $action = true;
                $whichAbsen = 'Pertama';
            } else {
                $lastAbsen = substr(Carbon::parse($absen->created_at)->format('H:i:s'), 0, 2);
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
