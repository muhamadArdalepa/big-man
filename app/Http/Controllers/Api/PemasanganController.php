<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Pemasangan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PemasanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Pemasangan::select(
            'pemasangans.id',
            'pelanggan.nama as pelanggan',
            'marketer.nama as marketer',
            'alamat',
            'status',
            'pemasangans.created_at',
        )
            ->leftJoin('users as pelanggan', 'pelanggan', '=', 'pelanggan.id')
            ->leftJoin('users as marketer', 'marketer', '=', 'marketer.id');
        if ($request->has('wilayah') && $request->wilayah != '') {
            $query->where('pelanggan.wilayah_id', $request->wilayah);
        }
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->where('pemasangans.created_at', 'LIKE', '%' . $request->tanggal . '%');
        }
        $pemasangans = $query->get();
        foreach ($pemasangans as $pemasangan) {
            $pemasangan->created_atFormat = Carbon::parse($pemasangan->created_at)->format('H:i');
        }
        return response()->json($pemasangans);
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
        return response($request->foto_ktp);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pemasangan = Pemasangan::with('pelanggan', 'marketer')->find($id);
        $carbonDateTime = Carbon::parse($pemasangan->created_at);
        $pemasangan->waktuFormat = $carbonDateTime->translatedFormat('l, d F Y');
        $pemasangan->tanggalFormat = $carbonDateTime->format('H:i');
        return response()->json($pemasangan);
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
    public function update(Request $request, Pemasangan $pemasangan)
    {
        $validatedData = $request->validate([
            'nik' => 'nullable|numeric|digits:16',
            'alamat' => 'nullable|string',
            'koordinat_rumah' => ['nullable', 'string', 'regex:/^(-?\d{1,2}(?:\.\d+)?),\s*(-?\d{1,3}(?:\.\d+)?)$/i'],
            'koordinat_odp' => ['nullable', 'string', 'regex:/^(-?\d{1,2}(?:\.\d+)?),\s*(-?\d{1,3}(?:\.\d+)?)$/i'],
            'serial_number' => 'nullable|string',
            'ssid' => 'nullable|string',
            'password' => 'nullable|string',
            'paket_id' => 'nullable|integer',
            'hasil_opm_user' => 'numeric',
            'hasil_opm_odp' => 'numeric',
            'kabel_tepakai' => 'nullable|integer',
            'port_odp' => 'nullable|string',
            'ket' => 'nullable|string',
        ], [
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.digits' => 'NIK harus memiliki panjang 16 digit.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'koordinat_rumah.string' => 'Koordinat rumah harus berupa teks.',
            'koordinat_rumah.regex' => 'Format koordinat rumah tidak valid.',
            'koordinat_odp.string' => 'Koordinat ODP harus berupa teks.',
            'koordinat_odp.regex' => 'Format koordinat ODP tidak valid.',
            'serial_number.string' => 'Serial number harus berupa teks.',
            'ssid.string' => 'SSID harus berupa teks.',
            'password.string' => 'Password harus berupa teks.',
            'hasil_opm_user.numeric' => 'Hasil OPM User harus berupa angka.',
            'hasil_opm_odp.numeric' => 'Hasil OPM ODP harus berupa angka.',
            'kabel_tepakai.integer' => 'Jumlah kabel yang telah dipakai harus berupa angka.',
            'port_odp.string' => 'Port ODP harus berupa teks.',
            'ket.string' => 'Keterangan harus berupa teks.',
        ]);

        $pemasangan->fill($validatedData);
        $pemasangan->save();
        return response(['message' => 'Data berhasil diperbarui']);
    }

    public function update_foto(Request $request, Pemasangan $pemasangan)
    {
        $validatedData = $request->validate([
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg,jfif,avif',
            'foto_rumah' => 'nullable|image|mimes:jpeg,png,jpg,jfif,avif',
            'foto_modem' => 'nullable|image|mimes:jpeg,png,jpg,jfif,avif',
            'foto_letak_modem' => 'nullable|image|mimes:jpeg,png,jpg,jfif,avif',
            'foto_opm_user' => 'nullable|image|mimes:jpeg,png,jpg,jfif,avif',
            'foto_opm_odp' => 'nullable|image|mimes:jpeg,png,jpg,jfif,avif'
        ]);

        $uploadedImages = [];

        foreach ($validatedData as $key => $value) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $imageName = 'pemasangan/' . uniqid() . '.' . $file->getClientOriginalExtension();

                $compressedImage = Image::make($file->getRealPath());
                $compressedImage->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $compressedImagePath = 'private/' . $imageName;
                Storage::put($compressedImagePath, (string) $compressedImage->encode());

                $uploadedImages[$key] = $imageName;
            }
        }


        $pemasangan->fill($uploadedImages);
        $pemasangan->save();
        return response(['message' => 'Data berhasil diperbarui']);
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
