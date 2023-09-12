<?php

namespace App\Http\Controllers\Api;

use App\Models\Other;
use App\Models\Laporan;
use App\Models\Aktivitas;
use App\Models\Pekerjaan;
use App\Models\Pemasangan;
use App\Models\TimAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

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
            'pekerjaans.*'
        )
            ->join('tims', 'pekerjaans.tim_id', '=', 'tims.id')
            ->join('tim_anggotas', 'tims.id', '=', 'tim_anggotas.tim_id')
            ->where('tim_anggotas.user_id', auth()->user()->id)
            ->where('tim_anggotas.user_id', auth()->user()->id)
            ->latest()->get();
        $pekerjaans->map(function ($pekerjaan) {
            $pekerjaan->route_id = $pekerjaan->getRouteKey();
            if ($pekerjaan->pemasangan_id) {
                $pemasangan = Pemasangan::select('users.nama', 'alamat','status')->join('users', 'pelanggan_id', '=', 'users.id')
                    ->find($pekerjaan->pemasangan_id);
                $pekerjaan->detail = $pemasangan->nama . ' - ' . $pemasangan->alamat;
                $pekerjaan->nama_pekerjaan = 'Pemasangan';
                $pekerjaan->status = $pemasangan->getStatus();
            }
            if ($pekerjaan->laporan_id) {
                $laporan = Laporan::select('users.nama', 'pemasangans.alamat','laporans.status')->join('users', 'pelanggan_id', '=', 'users.id')
                    ->join('pemasangans', 'pelanggan_id', '=', 'pemasangans.pelanggan_id')->find($pekerjaan->laporan_id);
                $pekerjaan->detail = $laporan->nama . ' - ' . $laporan->alamat;
                $pekerjaan->nama_pekerjaan = 'Perbaikan';
                $pekerjaan->status = $laporan->status;

            }
            if ($pekerjaan->other_id) {
                $other = Other::find($pekerjaan->other_id);
                $pekerjaan->detail = $other->detail . ' - ' . $other->alamat;
                $pekerjaan->nama_pekerjaan = $other->nama_pekerjaan;
                $pekerjaan->status = $other->status;

            }

            $pekerjaan->created_atFormat = Carbon::parse($pekerjaan->created_at)->translatedFormat('l, d F Y | H:i');
            $pekerjaan->updated_atFormat = Carbon::parse($pekerjaan->updated_at)->translatedFormat('l, d F Y | H:i');
            return $pekerjaan;
        });
        return response()->json($pekerjaans);
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

            $compressedImagePath = 'private/' . $fotoName;
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
