<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use App\Models\User;
use App\Models\Absen;
use App\Models\Paket;
use App\Models\Laporan;
use App\Models\Wilayah;
use App\Models\Aktivitas;
use App\Models\Pekerjaan;
use App\Models\Pemasangan;
use App\Models\TimAnggota;
use App\Models\JenisGangguan;
use App\Models\JenisPekerjaan;
use App\Models\Other;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class PageController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('id');
    }

    public function pekerjaan_show($route_id)
    {
        try {
            $id = Crypt::decrypt($route_id);
        } catch (\Throwable $th) {
            abort(404);
        }
        $this->addBreadcrumb('Pekerjaan', url('pekerjaan'));
        $this->addBreadcrumb('Pekerjaan ' . $id, route('pekerjaan.show', $id));
        $pekerjaan = Pekerjaan::findOrFail($id);
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
            case 2:
                $detail = Laporan::select(
                    'laporans.*',
                    'users.nama',
                    'users.no_telp',
                    'users.email',
                    'users.foto_profil',
                    'penerima.nama as penerima'
                )
                    ->leftJoin('users', 'pelapor', '=', 'users.id')
                    ->leftJoin('users as penerima', 'penerima', '=', 'penerima.id')
                    ->findOrFail($pekerjaan->laporan_id);
                break;
            default:
                $detail = $pekerjaan->detail;
                break;
        }
        // dd($detail);
        return view('pages.pekerjaan-show', compact('pekerjaan', 'detail'));
    }
    public function pemasangan()
    {
        switch (auth()->user()->role) {
            case 1:
                $this->addBreadcrumb('pemasangan', route('pemasangan.index'));
                $wilayahs = Wilayah::all();
                $date = date('Y-m-d');
                $pakets = Paket::all();
                $pemasangan = Pemasangan::with('pelanggan', 'pekerjaan', 'paket')->first();

                $pemasangan->route_id = $pemasangan->getRouteKey();
                $pemasangan->status = $pemasangan->getStatus();
                $pemasangan->created_atFormat = Carbon::parse($pemasangan->created_at)->format('H:i');

                return view('pages.admin.pemasangan', compact('wilayahs', 'date', 'pakets', 'pemasangan'));

            case 3:
                $pemasangan = Pemasangan::where('pelanggan_id', auth()->user()->id)->first();
                $this->addBreadcrumb($pemasangan ? 'Daftar Jaringan' : 'Detail Pemasangan', route('pemasangan.index'));
                if ($pemasangan) {
                    if ($pemasangan->status == 1) {
                        $detail = Pemasangan::with('pekerjaan', 'user')->where('pelanggan', auth()->user()->id)->first();
                        return view('pages.pemasangan-show', compact('detail'));
                    } else {
                        $detail = Pemasangan::with('pekerjaan', 'user')->where('pelanggan', auth()->user()->id)->first();
                        $pekerjaan = Pekerjaan::with('jenis_pekerjaan')->where('pemasangan_id', $pemasangan->id)->first();
                        return view('pages.pekerjaan-show', compact('pekerjaan', 'detail'));
                    }
                }
                $pakets = Paket::all();
                return view('pages.pelanggan.pemasangan-form', compact('pemasangan', 'pakets'));
            default:
                abort(404);
        }
    }

    public function pemasangan_show($id)
    {
        $this->addBreadcrumb('pemasangan', route('pemasangan.index'));
        $this->addBreadcrumb('pemasangan ' . $id, route('pemasangan.show', $id));
        $pemasangan = Pemasangan::with('user', 'marketer', 'paket')->find($id);
        $pakets = Paket::all();
        return view('pages.pelanggan.pemasangan-form', compact('pemasangan', 'pakets'));
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
        $this->addBreadcrumb('tim', route('tim'));
        switch (auth()->user()->role) {
            case 1:
                $date = date('Y-m-d');
                $wilayahs = Wilayah::all();
                return view('pages.admin.tim', compact('wilayahs', 'date'));
                break;
            case 2:
                return view('pages.teknisi.tim');
                break;

            default:
                break;
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

    public function wilayah()
    {
        $this->addBreadcrumb('wilayah', route('wilayah'));
        return view('pages.admin.wilayah');
    }
    public function paket()
    {
        $this->addBreadcrumb('paket', route('paket'));
        return view('pages.admin.paket');
    }

    public function auth_profile()
    {
    }
}
