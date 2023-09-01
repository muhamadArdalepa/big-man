<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Aktivitas;
use App\Models\Pemasangan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    public function data_pelanggan(Request $request)
    {
        $pelanggan = User::select(
            'nama',
            'email',
            'no_telp',
            'alamat',
            'koordinat_rumah'
        )
            ->join('pemasangans', 'users.id', '=', 'pelanggan')
            ->where('wilayah_id', $request->wilayah)
            ->where('role', 3)
            ->where('nama', 'LIKE', $request->nama)
            ->first();
        return response()->json($pelanggan);
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
        $pelangganData = $request->validate([
            'wilayah_id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'password' => 'required|string|min:6',
        ]);

        $pemasanganData = $request->validate([
            'nik' => 'required|numeric|digits:16',
            'paket_id' => 'required|integer',
            'alamat' => 'required|string',
            'koordinat_rumah' => 'required|string',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg',
            'foto_rumah' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        $pelanggan = User::where([
            'nama' => $pelangganData['nama'],
            'role' => 3,
            'no_telp' => $pelangganData['no_telp'],
            'email' => $request->email,
            'wilayah_id' => $pelangganData['wilayah_id'],
        ])->first();

        if (!$pelanggan) {
            $request->validate([
                'email' => 'required|email|unique:users,email|max:255',
            ]);

            $pelanggan = User::create([
                'nama' => $pelangganData['nama'],
                'role' => 3,
                'no_telp' => $pelangganData['no_telp'],
                'email' => $request->email,
                'password' => $pelangganData['password'],
                'wilayah_id' => $pelangganData['wilayah_id'],
                'foto_profil' => 'profile/dummy.png',
            ]);
        }

        $pemasanganData['pelanggan'] = $pelanggan->id;
        $pemasanganData['status'] = 'menunggu konfirmasi';
        $pemasanganData['marketer'] = auth()->user()->id;


        foreach (['foto_ktp', 'foto_rumah'] as $field) {
            if ($request->hasFile($field)) {
                $fotoName = uniqid() . '.' . $request->file($field)->getClientOriginalExtension();
                $compressedImage = Image::make($request->file($field)->getRealPath());
                $compressedImage->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $folderPath = 'pemasangan/' . $fotoName;
                Storage::put('private/' . $folderPath, (string) $compressedImage->encode());
                $pemasanganData[$field] = $folderPath;
            }
        }

        if (Pemasangan::create($pemasanganData)) {
            return response()->json(['message' => 'Pemasangan baru berhasil ditambahkan']);
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
        $pemasangan = Pemasangan::with('pelanggan', 'marketer')->find($id);
        $createDateTime = Carbon::parse($pemasangan->created_at);
        $updateDateTime = Carbon::parse($pemasangan->updated_at);
        $pemasangan->created_atFormat = $createDateTime->translatedFormat('l, d F Y | H:i');
        $pemasangan->updated_atFormat = $updateDateTime->translatedFormat('l, d F Y | H:i');
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
    public function destroy(Pemasangan $pemasangan)
    {
        $pemasangan->delete();
        return response(['message' => 'Pemasangan berhasil dihapus']);
    }
}
