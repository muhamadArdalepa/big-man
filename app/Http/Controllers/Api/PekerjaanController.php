<?php

namespace App\Http\Controllers\Api;

use App\Models\Tim;
use App\Models\Laporan;
use App\Models\Aktivitas;
use App\Models\Pekerjaan;
use App\Models\Pemasangan;
use App\Models\TimAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\JenisPekerjaan;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class PekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Pekerjaan::select(
            'pekerjaans.id',
            'tim_id',
            'users.foto_profil',
            'jenis_pekerjaan_id',
            'jenis_pekerjaans.nama_pekerjaan',
            'pemasangan_id',
            'laporan_id',
            'detail',
            'pekerjaans.status'
        )
            ->join('tims', 'tim_id', '=', 'tims.id')
            ->join('users', 'tims.user_id', '=', 'users.id')
            ->join('jenis_pekerjaans', 'jenis_pekerjaan_id', '=', 'jenis_pekerjaans.id')
            ->join('wilayahs', 'pekerjaans.wilayah_id', '=', 'wilayahs.id');

        $wilayah = $request->input('wilayah');
        $tanggal = '%' . $request->input('tanggal') . '%';

        if ($request->has('wilayah') && !empty($wilayah)) {
            $query->where('pekerjaans.wilayah_id', $wilayah);
        }

        if ($request->has('tanggal') && !empty($tanggal)) {
            $query->where('pekerjaans.created_at', 'LIKE', $tanggal);
        }

        $pekerjaans = $query->get();
        foreach ($pekerjaans as $i => $pekerjaan) {
            foreach (TimAnggota::select('users.nama')->leftJoin('users', 'users.id', '=', 'user_id')->where('tim_id', $pekerjaan->tim_id)->get() as $j => $a) {
                $pekerjaan->anggota .= ($j > 0 ? '<br>' : '') . $a->nama;
            }
            switch ($pekerjaan->jenis_pekerjaan_id) {
                case 1:
                    $pemasangan = Pemasangan::select(
                        'users.nama',
                        'alamat'
                    )
                        ->join('users', 'pelanggan', '=', 'users.id')
                        ->find($pekerjaan->pemasangan_id);
                    $pekerjaan->detail = $pemasangan->nama . '<br><div class="text-sm lh-sm opacity-7">' . $pemasangan->alamat . '<div/>';
                    break;
                case 2:
                    $laporan = Laporan::select(
                        'users.nama',
                        'pemasangans.alamat'
                    )
                        ->join('users', 'pelapor', '=', 'users.id')
                        ->join('pemasangans', 'pelanggan', '=', 'pemasangans.pelanggan')
                        ->find($pekerjaan->laporan_id);
                    $pekerjaan->detail = $laporan->nama . '<br><div class="text-sm lh-sm opacity-7">' . $laporan->alamat . '<div/>';
                    break;
                default:
                    $pekerjaan->detail = $pekerjaan->detail;
                    break;
            };
            $pekerjaan->created_atFormat = Carbon::parse($pekerjaan->created_at)->format('H:i');
            $pekerjaan->updated_atFormat = Carbon::parse($pekerjaan->updated_at)->format('H:i');
        }
        return response()->json($pekerjaans);
    }

    public function select2_tim(Request $request)
    {
        if (auth()->user()->role == 1) {
            $tims = Tim::select(
                'tims.id',
                'user_id',
                'nama',
            )
                ->join('users', 'user_id', '=', 'users.id')
                ->where('status', 'Standby')
                ->orderBy('tims.id', 'asc')
                ->groupBy('tims.id');

            if ($request->has('wilayah') && !empty($request->wilayah)) {
                $tims->where('users.wilayah_id', $request->wilayah);
            }

            $data = $tims->get();
            foreach ($data as $i => $tim) {
                $tim->text = $data[$i]->nama . '(ketua)';
                foreach (TimAnggota::select('users.nama')
                    ->where('tim_id', $tim->id)
                    ->whereNot('user_id', $data[$i]->user_id)
                    ->join('users', 'user_id', '=', 'users.id')
                    ->get() as $j => $a) {
                    $tim->text .= ', ' . $a->nama;
                }
            }

            return response()->json($data);
        }
    }
    public function select2_pemasangan(Request $request)
    {
        $query = Pemasangan::select(
            'pemasangans.id',
            'nama',
            'alamat',
        )
            ->join('users', 'pelanggan', '=', 'users.id')
            ->where('status', 'menunggu konfirmasi');
            // ->whereDate('pemasangans.created_at', '=', Carbon::today());

        if ($request->has('terms') && !empty($request->terms)) {
            $query->where('nama', 'LIKE', '%' . $request->terms . '%')
                ->orWhere('alamat', 'LIKE', '%' . $request->terms . '%');
        }



        $data = [
            "results" => $query->get(),
            "pagination" => [
                "more" => false
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
        // return $request->all();
        switch ($request->jenis_pekerjaan_id) {
            case 0:
                $validatedData = $request->validate([
                    'jenis_pekerjaan_id' => 'required',
                    'tim_id' => 'required',
                    'wilayah_id' => 'required',
                    'poin' => 'required',
                    'nama_pekerjaan' => 'required',
                    'detail' => 'required',
                    'alamat' => 'required',
                ], [
                    'jenis_pekerjaan_id.required' => 'Pilih jenis pekerjaan.',
                    'tim_id.required' => 'Pilih tim.',
                    'wilayah_id.required' => 'Wilayah harus diisi.',
                    'poin.required' => 'Poin harus diisi.',
                    'nama_pekerjaan.required' => 'Nama pekerjaan harus diisi.',
                    'detail.required' => 'Detail pekerjaan harus diisi.',
                    'alamat.required' => 'Alamat pekerjaan harus diisi.',
                ]);
                $jenisPekerjaan = JenisPekerjaan::create(['nama_pekerjaan' => $request->nama_pekerjaan]);
                $validatedData['jenis_pekerjaan_id'] = $jenisPekerjaan->id;
                break;

            case 1:
                $validatedData = $request->validate([
                    'jenis_pekerjaan_id' => 'required',
                    'tim_id' => 'required',
                    'wilayah_id' => 'required',
                    'poin' => 'required',
                    'pemasangan_id' => 'required',
                ], [
                    'jenis_pekerjaan_id.required' => 'Pilih jenis pekerjaan.',
                    'tim_id.required' => 'Pilih tim.',
                    'wilayah_id.required' => 'Wilayah harus diisi.',
                    'poin.required' => 'Poin harus diisi.',
                    'pemasangan_id.required' => 'Data pemasangan harus diisi.',
                ]);
                Pemasangan::find($request->pemasangan_id)->update(['status' => 'sedang diproses']);
                break;

            case 2:
                $validatedData = $request->validate([
                    'jenis_pekerjaan_id' => 'required',
                    'tim_id' => 'required',
                    'wilayah_id' => 'required',
                    'poin' => 'required',
                    'laporan_id' => 'required',
                ], [
                    'jenis_pekerjaan_id.required' => 'Pilih jenis pekerjaan.',
                    'tim_id.required' => 'Pilih tim.',
                    'wilayah_id.required' => 'Wilayah harus diisi.',
                    'poin.required' => 'Poin harus diisi.',
                    'laporan_id.required' => 'Data laporan harus diisi.',
                ]);
                Laporan::find($request->laporan_id)->update(['status' => 'sedang diproses']);
                break;

            default:
                $validatedData = $request->validate([
                    'jenis_pekerjaan_id' => 'required',
                    'tim_id' => 'required',
                    'wilayah_id' => 'required',
                    'poin' => 'required',
                    'detail' => 'required',
                    'alamat' => 'required',
                ], [
                    'jenis_pekerjaan_id.required' => 'Pilih jenis pekerjaan.',
                    'tim_id.required' => 'Pilih tim.',
                    'wilayah_id.required' => 'Wilayah harus diisi.',
                    'poin.required' => 'Poin harus diisi.',
                    'detail.required' => 'Detail pekerjaan harus diisi.',
                    'alamat.required' => 'Alamat pekerjaan harus diisi.',
                ]);
                break;
        }
        $validatedData['status'] = 'sedang diproses';
        $pekerjaan = Pekerjaan::create($validatedData);
        Tim::find($request->tim_id)->update(['status' => 'Bekerja']);
        Aktivitas::create([
            'user_id' => auth()->user()->id,
            'pekerjaan_id' => $pekerjaan->id,
            'aktivitas' => 'Pekerjaan dimulai',
        ]);
        return response([
            'message' => 'Pekerjaan baru telah ditambahkan',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = Pekerjaan::select(
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
            ->where('tim_anggotas.user_id', auth()->user()->id);

        $data = [];
        foreach ($query->get() as $i => $pekerjaan) {
            switch ($pekerjaan->jenis_pekerjaan_id) {
                case 1:
                    $pemasangan = Pemasangan::select('users.nama', 'alamat')->join('users', 'user_id', '=', 'users.id')->find($pekerjaan->pemasangan_id);
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
            $pekerjaan->created_atFormat = Carbon::parse($pekerjaan->created_at)->translatedFormat('d/m/Y');
            $pekerjaan->updated_atFormat = Carbon::parse($pekerjaan->updated_at)->translatedFormat('H:i');
            $data[$i] = $pekerjaan;
        }
        return response()->json($data);
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
    public function update(Request $request, Pekerjaan $pekerjaan)
    {
        $pekerjaan->update(['status' => $request->status]);
        $status = $request->status;
        switch ($request->jenis_pekerjaan_id) {
            case 1:
                switch ($request->status) {
                    case 'selesai':
                        $status = 'aktif';
                        break;
                    case 'dibatalkan':
                        $status = 'tidak aktif';
                        break;
                }
                Pemasangan::find($pekerjaan->pemasangan_id)->update(['status' => $status]);
                break;
            case 2:
                $status = $request->status == 'dibatalkan' ?? 'ditunda';
                Laporan::find($pekerjaan->laporan_id)->update(['status' => $status]);
                break;
        }
        if ($request->status == 'sedang diproses') {
            Tim::find($pekerjaan->tim_id)->update(['status' => 'Bekerja']);
        } else {
            Tim::find($pekerjaan->tim_id)->update(['status' => 'Standby']);
        }
        if ($request->status == 'selesai') {
            $anggotas = User::select('users.id', 'poin')
                ->where('tim_id', $pekerjaan->tim_id)
                ->join('tim_anggotas', 'user_id', '=', 'users.id')
                ->get();

            foreach ($anggotas as $anggota) {
                $anggota->poin += $request->poin;
                $anggota->save();
            }
        }
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
