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
                    'laporans.created_at as waktu'
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

                return response()->json($query->get());
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
                    $laporan->tanggalFormat = $carbonDatetime->translatedFormat('l, d F Y');
                    $laporan->waktuFormat = $carbonDatetime->translatedFormat('H:i');
                }
                return response()->json($laporans);
        }
    }

    public function select2_pelanggan(Request $request)
    {
        if (auth()->user()->role != 3) {
            $results = [];
            $pelanggans = User::select('users.id', 'nama')->join('pemasangans', 'user_id', '=', 'users.id')->where('role', 3)->whereNotNull('pemasangans.id');

            if ($request->has('wilayah') && !empty($request->wilayah)) {
                $pelanggans->where('wilayah_id', $request->wilayah);
            }

            if ($request->has('terms') && !empty($request->terms)) {
                $pelanggans->where('nama', 'LIKE', '%' . $request->terms . '%');
            }

            foreach ($pelanggans->get() as $i => $pelanggan) {
                $results[$i] = [
                    "id" => $pelanggan->id,
                    "text" => $pelanggan->nama,
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
    public function select2_tim(Request $request)
    {
        if (auth()->user()->role != 3) {

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
        return response()->json(
            User::select('nama', 'no_telp', 'email', 'serial_number', 'alamat')
                ->join('pemasangans', 'users.id', '=', 'users.id')
                ->find($id)
        );
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

        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:3',
            'email' => 'required|email',
            'wilayah_id' => 'required',
            'no_telp' => 'required|numeric|digits_between:11,15',
            'alamat' => 'required',
            'jenis_gangguan_id' => 'required',
            'serial_number' => 'required',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'nama.min' => 'Nama harus memiliki minimal 3 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'wilayah_id.required' => 'wilayah harus dipilih.',
            'no_telp.required' => 'Nomor telepon harus diisi.',
            'no_telp.numeric' => 'Nomor telepon harus berupa angka.',
            'no_telp.digits_between' => 'Nomor telepon memiliki minimal 11 karakter.',
            'alamat.required' => 'Alamat harus diisi.',
            'jenis_gangguan_id.required' => 'Pilih jenis gangguan.',
            'serial_number.required' => 'Serial number harus diisi.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $pelanggan = null;
            $pemasangan = null;
            if ($request->is_new == 'true') {
                $pelanggan = new User;
                $pelanggan->password = Str::random(6);
                $pelanggan->role = 3;
                $pemasangan = new Pemasangan;
                $pemasangan->user_id = $pelanggan->id;
            } else {
                $pelanggan = User::find($request->id);
                $pemasangan = Pemasangan::where('user_id', $request->id)->first();
            }
            $pelanggan->email = $request->email;
            $pelanggan->nama = ucwords(trim($request->nama));
            $pelanggan->no_telp = $request->no_telp;
            $pelanggan->save();
            $pemasangan->alamat = $request->alamat;
            $pemasangan->serial_number = $request->serial_number;
            $pemasangan->save();
            $laporan = new Laporan;
            $laporan->pelapor = $pelanggan->id;
            $laporan->penerima = auth()->user()->id;
            $laporan->jenis_gangguan_id = $request->jenis_gangguan_id;
            $laporan->status = 2;
            $laporan->ket = $request->ket;
            $laporan->save();
            return response()->json([
                'status' => 200,
                'message' => 'Laporan baru telah ditambahkan',
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $laporan = Laporan::select(
            'laporans.*',
            'users.nama',
            'users.foto_profil',
            'users.wilayah_id',
            'pemasangans.serial_number',
            'pakets.nama_paket',
            'jenis_gangguan_id',
            'pemasangans.alamat',
            'pemasangans.koordinat_rumah',
            'pemasangans.koordinat_odp',
            'pemasangans.port_odp',
            'pemasangans.port_odp',
        )
            ->join('users', 'pelapor', '=', 'users.id')
            ->join('pemasangans', 'pelapor', '=', 'pelanggan')
            ->join('pakets', 'pemasangans.paket_id', '=', 'pakets.id')
            ->join('jenis_gangguans', 'jenis_gangguan_id', '=', 'jenis_gangguans.id')
            ->find($id);
        $laporan->tanggalFormat = Carbon::parse($laporan->created_at)->translatedFormat('l, d F Y');
        $laporan->waktuFormat = Carbon::parse($laporan->created_at)->format('H:i');
        return response()->json($laporan);
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
