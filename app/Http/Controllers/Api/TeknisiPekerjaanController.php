<?php

namespace App\Http\Controllers\Api;

use App\Models\Laporan;
use App\Models\Aktivitas;
use App\Models\Pekerjaan;
use App\Models\Pemasangan;
use App\Models\TimAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class TeknisiPekerjaanController extends Controller
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
    public function index()
    {
        $pekerjaans = Pekerjaan::select(
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
        foreach ($pekerjaans->get() as $i => $pekerjaan) {
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
            $pekerjaan->created_atFormat = Carbon::parse($pekerjaan->created_at)->translatedFormat('d/m/Y');
            $pekerjaan->updated_atFormat = Carbon::parse($pekerjaan->updated_at)->translatedFormat('H:i');
            $data[$i] = $pekerjaan;
        }
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
        $validatedData = $request->validate([
            'pekerjaan_id' => 'required',
            'aktivitas' => 'required',
            'alamat' => 'required',
            'koordinat' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg'
        ], [
            'aktivitas.required' => 'Aktivitas harus diisi.',
            'koordinat.required' => 'Izinkan website mengakses GPS.',
            'alamat.required' => 'Izinkan website mengakses GPS.',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format file tidak valid',
        ]);

        $validatedData['user_id'] = auth()->user()->id;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = 'aktivitas/' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $compressedImage = Image::make($foto->getRealPath());

            $compressedImage->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $compressedImagePath = 'private/' .$fotoName;
            Storage::put($compressedImagePath, (string) $compressedImage->encode());

            $validatedData['foto'] = $fotoName;
        }
        $aktivitas = Aktivitas::create($validatedData);
        Pekerjaan::findOrFail($request->pekerjaan_id)
            ->update(['updated_at' => now()]);
        return response()->json(
            [
                'message' => 'Aktivitas baru berhasil ditambahkan',
                'foto' => $aktivitas->foto
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $aktivitass = Aktivitas::select(
            'aktivitas.id',
            'users.foto_profil',
            'users.nama',
            'aktivitas.created_at',
            'aktivitas.foto',
            'aktivitas.alamat',
            'aktivitas.koordinat',
            'aktivitas.aktivitas',
        )
            ->join('users', 'user_id', '=', 'users.id')
            ->where('pekerjaan_id', $id)->orderBy('created_at', 'desc')->get();
        return response()->json($aktivitass);
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
