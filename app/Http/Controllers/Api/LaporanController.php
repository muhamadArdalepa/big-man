<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Tim;
use App\Models\User;
use App\Models\Laporan;
use App\Models\Pemasangan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TimAnggota;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
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
        switch (auth()->user()->role) {
            case 1:
                $query = Laporan::select(
                    'laporans.id',
                    'pelapor.nama as pelapor',
                    'penerima.nama as penerima',
                    'nama_gangguan',
                    'status',
                    'laporans.created_at'
                )
                    ->join('users as pelapor', 'pelapor', '=', 'pelapor.id')
                    ->leftJoin('users as penerima', 'penerima', '=', 'penerima.id')
                    ->join('jenis_gangguans', 'jenis_gangguan_id', '=', 'jenis_gangguans.id');

                $wilayah = $request->input('wilayah');
                $tanggal = '%' . $request->input('tanggal') . '%';
                if ($request->has('wilayah') && !empty($wilayah)) {
                    $query->where('pelapor.wilayah_id', $wilayah);
                }
                if ($request->has('tanggal') && !empty($tanggal)) {
                    $query->where('laporans.created_at', 'LIKE', $tanggal);
                }
                $laporans = $query->get();
                foreach ($laporans as $laporan) {
                    $laporan->timeFormat = Carbon::parse($laporan->created_at)->format('H:i');
                }
                return response()->json($laporans);
                break;
            case 3:
                $query = Laporan::select(
                    'laporans.id',
                    'laporans.created_at',
                    'nama',
                    'nama_gangguan',
                    'status',
                )
                    ->leftJoin('users', 'penerima', '=', 'users.id')
                    ->leftJoin('jenis_gangguans', 'jenis_gangguan_id', '=', 'jenis_gangguans.id')
                    ->where('pelapor', auth()->user()->id);
                $laporans = $query->get();
                foreach ($laporans as $laporan) {
                    $carbonDatetime = Carbon::parse($laporan->created_at);
                    $laporan->dateFormat = $carbonDatetime->translatedFormat('l, d F Y');
                    $laporan->timeFormat = $carbonDatetime->translatedFormat('H:i');
                }
                return response()->json($laporans);
        }
    }

    public function select2_pelanggan(Request $request)
    {
        if (auth()->user()->role == 1) {
            $query = User::select(
                'users.id',
                'nama as text'
            )
                ->join('pemasangans', 'pelanggan', '=', 'users.id')
                ->where('role', 3)
                ->whereNotNull('pemasangans.id');

            if ($request->has('wilayah') && !empty($request->wilayah)) {
                $query->where('wilayah_id', $request->wilayah);
            }

            return response()->json($query->get());
        }
    }

    public function select2_tim(Request $request)
    {
        if (auth()->user()->role == 1) {
            $results = [];
            $tims = Tim::select(
                'tims.id',
                'users.foto_profil'
            )
                ->join('users', 'users.id', '=', 'user_id')
                ->join('tim_anggotas', 'tims.id', '=', 'tim_id')
                ->orderBy('tims.id', 'asc')
                ->groupBy('tims.id');

            if ($request->has('wilayah') && !empty($request->wilayah)) {
                $tims->where('users.wilayah_id', $request->wilayah);
            }

            if ($request->has('terms') && !empty($request->terms)) {
                $tims->where('users.nama', 'LIKE', '%' . $request->terms . '%');
            }

            foreach ($tims->get() as $i => $tim) {
                $anggota = '';
                foreach (TimAnggota::select('users.nama')
                    ->where('tim_id', $tim->id)
                    ->join('users', 'user_id', '=', 'users.id')->get() as $j => $a) {
                    $anggota .= ($j > 0 ? ' / ' : ' - ') . $a->nama;
                }
                $results[$i] = [
                    "id" => $tim->id,
                    "text" => 'TIM ' . $tim->id . $anggota,
                ];
            }

            $data = [
                "results" => $results,
                "pagination" => [
                    "more" => false // Ubah menjadi true jika ingin mengaktifkan fitur infinite scroll
                ]
            ];

            return response()->json($data);
        }
    }

    public function data_pelanggan($id)
    {
        if (auth()->user()->role == 1) {
            $user =  User::select(
                'serial_number',
                'alamat',
                'port_odp',
                'koordinat_rumah'
            )
                ->join('pemasangans', 'pelanggan', '=', 'users.id')
                ->find($id);
            return response()->json($user);
        }
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
        if (auth()->user()->role == 3) {
            $request['pelapor'] = auth()->user()->id;
        }
        $validatedData = $request->validate([
            'jenis_gangguan_id' => 'required',
            'pelapor' => 'required',
            'ket' => 'nullable|string'
        ], [
            'jenis_gangguan_id.required' => 'Jenis harus diisi',
            'pelapor.required' => 'Data pelanggan harus diisi',
            'ket.string' => 'Keterangan harus berupa string'
        ]);
        if (auth()->user()->role == 1) {
            $validatedData['status'] = 'menunggu konfirmasi';
            $validatedData['penerima'] = auth()->user()->id;
            $validatedData['recieve_at'] = now();
        }
        Laporan::create($validatedData);
        return response(['message' => 'Laporan gangguan berhasil ditambahkan']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (auth()->user()->role == 1) {
            $laporan = Laporan::select(
                'laporans.*',
                'users.nama',
                'users.wilayah_id',
                'pemasangans.serial_number',
                'jenis_gangguan_id',
                'pemasangans.alamat',
                'pemasangans.koordinat_rumah',
                'pemasangans.port_odp',
                'penerima.nama as penerima_nama',
                'laporans.created_at',
                'laporans.recieve_at',
            )
                ->join('users', 'pelapor', '=', 'users.id')
                ->leftJoin('users as penerima', 'penerima', '=', 'penerima.id')
                ->join('pemasangans', 'pelapor', '=', 'pelanggan')
                ->join('jenis_gangguans', 'jenis_gangguan_id', '=', 'jenis_gangguans.id')
                ->find($id);
            if ($laporan->recieve_at != null) {
                $carbonRecieve = Carbon::parse($laporan->recieve_atFormat);
                $laporan->recieve_atFormat = $carbonRecieve->translatedFormat('l, d F Y | H:i');
            }
            $carbonCreate = Carbon::parse($laporan->created_at);
            $laporan->created_atFormat = $carbonCreate->translatedFormat('l, d F Y | H:i');
            return response()->json($laporan);
        }
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
    public function update(Request $request, Laporan $laporan)
    {
        $validatedData = $request->validate([
            'jenis_gangguan_id' => 'required',
            'pelapor' => 'required',
            'ket' => 'nullable|string'
        ], [
            'jenis_gangguan_id.required' => 'Jenis harus diisi',
            'pelapor.required' => 'Data pelanggan harus diisi',
            'ket.string' => 'Keterangan harus berupa string'
        ]);
        $laporan->update($validatedData);
        return response(['message' => 'Laporan gangguan berhasil diubah']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Laporan $laporan)
    {
        $laporan->delete();
        return response(['message' => 'Laporan berhasil dihapus']);
    }
}
