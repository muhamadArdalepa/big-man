<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Laporan;
use App\Models\Pemasangan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $laporans = Laporan::with('pelapor', 'penerima', 'jenis_gangguan');
        $kota = $request->input('kota');
        $tanggal = '%' . $request->input('tanggal') . '%';
        if ($request->has('kota') && !empty($kota)) {
            $laporans->whereHas('pelapor', function ($query) use ($kota) {
                $query->where('kota_id', $kota);
            });
        }
        if ($request->has('tanggal') && !empty($tanggal)) {
            $laporans->where('created_at', 'LIKE', $tanggal);
        }

        return response()->json($laporans->get());
    }

    public function select2_pelanggan(Request $request)
    {
        $results = [];
        $pelanggans = User::with('kota')->where('role', 3);

        if ($request->has('kota') && !empty($request->kota)) {
            $pelanggans->where('kota_id', $request->kota);
        }

        if ($request->has('terms') && !empty($request->terms)) {
            $pelanggans->where('nama', 'LIKE', '%' . $request->terms . '%');
        }

        $pelangganData = $pelanggans->get();
        foreach ($pelangganData as $i => $pelanggan) {
            $results[$i] = [
                "id" => $pelanggan->id,
                "text" => $pelanggan->nama, // Ubah sesuai dengan atribut yang sesuai dari objek pelanggan
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
            'kota_id' => 'required',
            'no_telp' => 'required|numeric|digits_between:11,15',
            'alamat' => 'required',
            'jenis_gangguan_id' => 'required',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'nama.min' => 'Nama harus memiliki minimal 3 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'kota_id.required' => 'Kota harus dipilih.',
            'no_telp.required' => 'Nomor telepon harus diisi.',
            'no_telp.numeric' => 'Nomor telepon harus berupa angka.',
            'no_telp.digits_between' => 'Nomor telepon memiliki minimal 11 karakter.',
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
                $pelanggan->password = bcrypt(Str::random(6));
                $pelanggan->role = 3;
                $pemasangan = new Pemasangan;
                $pemasangan->user_id = $pelanggan->id;
            } else {
                $pelanggan = User::findOrFail($request->id);
                $pemasangan = Pemasangan::where('user_id', $request->id)->first();
            }
            $pelanggan->email = $request->email;
            $pelanggan->nama = ucwords(trim($request->nama));
            $pelanggan->no_telp = $request->no_telp;
            $pelanggan->foto_profil = 'dummy.png';
            $pelanggan->save();
            $pemasangan->alamat = $request->alamat;
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
                'message' => 'Pelanggan baru telah ditambahkan',
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
