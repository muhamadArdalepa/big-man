<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absen;
use App\Models\Laporan;
use App\Models\Aktivitas;
use App\Models\Pekerjaan;
use App\Models\Pemasangan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        switch (auth()->user()->role) {
            case 1:
                $wilayah_id = auth()->user()->wilayah_id;
                $data['pekerjaan'] = Pekerjaan::queryPekerjaanWithWilayah($wilayah_id)->limit(2)->get();
                $data['pemasangan'] = Pemasangan::join('users', 'pelanggan_id', '=', 'users.id')
                    ->where([
                        'status' => 1,
                        'wilayah_id' => $wilayah_id
                    ])->whereMonth('pemasangans.created_at', date('m'));

                $data['permintaan'] = Pemasangan::join('users', 'pelanggan_id', '=', 'users.id')
                    ->where([
                        'status' => 'menunggu konfirmasi',

                        'wilayah_id' => $wilayah_id
                    ])->whereMonth('pemasangans.created_at', date('m'))->limit(3)->get();


                $data['nLaporan'] = Laporan::join('users', 'pelanggan_id', '=', 'users.id')
                    ->where([
                        'status' => 'aktif',
                    ])->whereMonth('laporans.created_at', date('m'));

                $data['laporans'] = Laporan::join('users', 'pelanggan_id', '=', 'users.id')
                    ->where([
                        'status' => 'menunggu konfirmasi',
                        'wilayah_id' => $wilayah_id
                    ])->whereMonth('laporans.created_at', date('m'))->limit(3)->get();


                $data['absen'] = Absen::join('users', 'user_id', '=', 'users.id')
                    ->with('user')
                    ->where('wilayah_id', $wilayah_id)
                    ->whereDate('absens.created_at', date('Y-m-d'));

                $data['teknisi'] = User::where(['role' => 2, 'wilayah_id' => $wilayah_id])->orderBy('poin', 'desc');

                $p = Pemasangan::selectRaw('DAY(pemasangans.created_at) as tanggal, COUNT(*) as jumlah')
                    ->join('users', 'pelanggan_id', '=', 'users.id')
                    ->where([
                        'status' => 'aktif',
                        'wilayah_id' => $wilayah_id
                    ])
                    ->whereMonth('pemasangans.created_at', date('m'))
                    ->groupBy('tanggal');

                $l = Laporan::selectRaw('DAY(laporans.created_at) as tanggal, COUNT(*) as jumlah')
                    ->join('users', 'pelanggan_id', '=', 'users.id')
                    ->where([
                        'wilayah_id' => $wilayah_id
                    ])
                    ->whereMonth('laporans.created_at', date('m'))
                    ->orderBy('tanggal')
                    ->groupBy('tanggal');

                $chart = [
                    $p->pluck('tanggal'),
                    $p->pluck('jumlah'),
                    $l->pluck('tanggal'),
                    $l->pluck('jumlah'),
                ];

                $aktivitas = Aktivitas::join('users', 'user_id', '=', 'users.id')
                    ->where(['wilayah_id' => $wilayah_id])
                    ->orderBy('aktivitas.created_at', 'asc')->get();
                return view('pages.admin.dashboard', compact('data', 'chart', 'aktivitas'));
            case 2:
                return view('pages.teknisi.dashboard');
            case 3:
                return view('pages.pelanggan.dashboard');
            default:
                abort(404);
        }
    }
}
