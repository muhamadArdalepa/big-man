<?php

namespace App\Http\Controllers;

use App\Models\Other;
use App\Models\Paket;
use App\Models\Laporan;
use App\Models\Wilayah;
use App\Models\Pekerjaan;
use App\Models\Pemasangan;
use App\Models\JenisGangguan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

class PekerjaanController extends Controller
{
    public function index()
    {
        $this->addBreadcrumb('pekerjaan', url('pekerjaan'));
        $wilayahs = Wilayah::all();
        $date = date('Y-m-d');
        switch (auth()->user()->role) {
            case 1:
                return view('pages.admin.pekerjaan', compact('wilayahs', 'date'));
            case 2:
                $pekerjaan = Pekerjaan::select(
                    'pekerjaans.*'
                )
                    ->join('tims', 'pekerjaans.tim_id', '=', 'tims.id')
                    ->join('tim_anggotas', 'tims.id', '=', 'tim_anggotas.tim_id')
                    ->where('tim_anggotas.user_id', auth()->user()->id)
                    ->where('tim_anggotas.user_id', auth()->user()->id)
                    ->latest()->first();
                if ($pekerjaan) {

                    $pekerjaan->route_id = $pekerjaan->getRouteKey();
                    if ($pekerjaan->pemasangan_id) {
                        $pemasangan = Pemasangan::select('users.nama', 'alamat')->join('users', 'pelanggan_id', '=', 'users.id')
                            ->find($pekerjaan->pemasangan_id);
                        $pekerjaan->detail = $pemasangan->nama . ' - ' . $pemasangan->alamat;
                        $pekerjaan->nama_pekerjaan = 'Pemasangan';
                    }
                    if ($pekerjaan->laporan_id) {
                        $laporan = Laporan::select('users.nama', 'pemasangans.alamat')->join('users', 'pelanggan_id', '=', 'users.id')
                            ->join('pemasangans', 'user_id', '=', 'pemasangans.user_id')->find($pekerjaan->laporan_id);
                        $pekerjaan->detail = $laporan->nama . ' - ' . $laporan->alamat;
                        $pekerjaan->nama_pekerjaan = 'Perbaikan';
                    }
                    if ($pekerjaan->other_id) {
                        $other = Other::find($pekerjaan->other_id);
                        $pekerjaan->detail = $other->detail . ' - ' . $other->alamat;
                        $pekerjaan->nama_pekerjaan = $other->nama_pekerjaan;
                    }

                    $pekerjaan->created_atFormat = Carbon::parse($pekerjaan->created_at)->translatedFormat('H:i');
                    $pekerjaan->updated_atFormat = Carbon::parse($pekerjaan->updated_at)->translatedFormat('H:i');
                }
                return view('pages.teknisi.pekerjaan', compact('pekerjaan'));
            default:
                abort(404);
        }
    }

    public function show($route_id)
    {
        try {
            $id = Crypt::decrypt($route_id);
            return $id;
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404);
        }
    }
}
