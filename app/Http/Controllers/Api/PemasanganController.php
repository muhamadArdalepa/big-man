<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Tim;
use App\Models\User;
use App\Models\Aktivitas;
use App\Models\Pekerjaan;
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
        $query = Pemasangan::with('pelanggan:id,wilayah_id,nama,email,no_telp,foto_profil', 'marketer:id,nama,speciality,foto_profil', 'pekerjaan:id,tim_id,pemasangan_id,poin', 'paket:id,nama_paket');

        if ($request->has('wilayah') && $request->wilayah != '') {
            $wilayah_id = $request->wilayah;
            $query->whereHas('pelanggan', function ($query) use ($wilayah_id) {
                $query->where('wilayah_id', $wilayah_id);
            });
        }

        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->where('created_at', 'LIKE', '%' . $request->tanggal . '%');
        }

        $pemasangans = $query->get();
        $pemasangans->map(function ($pemasangan) {
            $pemasangan->route_id = $pemasangan->getRouteKey();
            $pemasangan->get_status = $pemasangan->getStatus();
            $pemasangan->wilayah = $pemasangan->getWilayah();
            $pemasangan->created_atFormat = Carbon::parse($pemasangan->created_at)->format('H:i');
            if ($pemasangan->pekerjaan) {
                $pemasangan->pekerjaan->route_id = $pemasangan->pekerjaan->getRouteKey();
                $pemasangan->pekerjaan->tim = $pemasangan->pekerjaan->getFullTim();
            }
            return $pemasangan;
        });
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

    public function select2_pelanggan(Request $request)
    {
        $wilayah_id = $request->wilayah;
        $query = User::select('id', 'nama as text', 'foto_profil', 'no_telp', 'email')->with('pemasangan')
            ->where([
                'wilayah_id' => $wilayah_id,
                'role' => 3
            ])
            ->whereDoesntHave('pemasangan');

        if ($request->has('nama') && !empty($request->nama)) {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        $data = [
            "results" => $query->get(),
            "pagination" => [
                "more" => false
            ]
        ];

        return response()->json($data);
    }

    public function select2_marketer(Request $request)
    {
        $wilayah_id = $request->wilayah;
        $query = User::select('id', 'nama as text', 'foto_profil', 'speciality')
            ->where([
                'wilayah_id' => $wilayah_id,
                'role' => 2
            ]);

        if ($request->has('nama') && !empty($request->nama)) {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
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
        $pemasanganData = $request->validate([
            'pelanggan_id' => 'required',
            'marketer_id' => 'nullable',
            'nik' => 'required|numeric|digits:16',
            'alamat' => 'required',
            'koordinat_rumah' => 'required',
            'foto_ktp' => 'required|image',
            'foto_rumah' => 'required|image',
            'paket_id' => 'required',
            'total_tarikan' => 'nullable',
        ]);

        $pemasanganData['status'] = 1;
        foreach (['foto_ktp', 'foto_rumah'] as $field) {
            if ($request->hasFile($field)) {
                $fotoName = uniqid() . '.' . $request->file($field)->getClientOriginalExtension();
                $compressedImage = Image::make($request->file($field)->getRealPath());
                $compressedImage->resize(700, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $folderPath = 'pemasangan/' . $fotoName;
                Storage::put('private/' . $folderPath, (string) $compressedImage->encode());
                $pemasanganData[$field] = $folderPath;
            }
        }

        if (Pemasangan::create($pemasanganData)) {
            return response(['message' => 'Pemasangan baru berhasil ditambahkan']);
        }
    }
    public function store_pekerjaan(Request $request, Pemasangan $pemasangan)
    {
        $validatedData = $request->validate([
            'poin' => 'required',
            'tim_id' => 'required',
        ]);
        try {
            $validatedData['pemasangan_id'] = $pemasangan->id;
            Pekerjaan::create($validatedData);
            $pemasangan->update([
                'status' => 2
            ]);
            Tim::find($validatedData['tim_id'])->update([
                'status' => 2
            ]);
            return response([
                'message' => 'Berhasil menambahkan pekerjaan'
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Gagal menambahkan pekerjaan'
            ], 400);
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
        return Pemasangan::with('pelanggan')->findOrFail($id);
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
        $pemasanganData = $request->validate([
            'pelanggan_id' => 'required',
            'nik' => 'required|numeric|digits:16',
            'alamat' => 'required',
            'koordinat_rumah' => 'required',
            'foto_ktp' => 'nullable|image',
            'foto_rumah' => 'nullable|image',
            'paket_id' => 'required',
        ]);

        foreach (['foto_ktp', 'foto_rumah'] as $field) {
            if ($request->hasFile($field)) {
                $fotoName = uniqid() . '.' . $request->file($field)->getClientOriginalExtension();
                $compressedImage = Image::make($request->file($field)->getRealPath());
                $compressedImage->resize(700, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $folderPath = 'pemasangan/' . $fotoName;
                Storage::put('private/' . $folderPath, (string) $compressedImage->encode());
                $pemasanganData[$field] = $folderPath;
            }
        }

        if ($pemasangan->update($pemasanganData)) {
            return response(['message' => 'Data pemasangan berhasil diubah']);
        }
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
