<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use App\Models\User;
use App\Models\Aktivitas;
use App\Models\TimAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->addBreadcrumb('profile', route('profile.index'));
        $aktivitass = Aktivitas::where('user_id',auth()->user()->id)->latest()->get();
        switch (auth()->user()->role) {
            case 1:
                return view('pages.profile',compact('aktivitass'));
                break;
            case 2:
                $tims = Tim::select('tims.id', 'user_id', 'users.nama as ketua', 'foto_profil', 'status')->join('users', 'user_id', '=', 'users.id')->get();
                foreach ($tims as $i => $tim) {
                    $tim->anggota = TimAnggota::select('user_id', 'nama', 'foto_profil')->join('users', 'user_id', '=', 'users.id')->where('tim_id', $tim->id)->get();
                }
                $data = [];
                foreach ($tims as $i => $tim) {
                    $isHasId = false;
                    foreach ($tim->anggota as $anggota) {
                        if ($anggota->user_id == auth()->user()->id) {
                            $isHasId = true;
                        }
                    }
                    if ($tim->user_id == auth()->user()->id || $isHasId) {
                        $data[$i] = $tim;
                    }
                }
                return view('pages.auth-profile', with(['tims' => $data,'aktivitass'=>$aktivitass]));
                break;

            default:
                # code...
                break;
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
    public function update(Request $request,$id)
    {
        // Validasi data yang diterima dari request
        // dd($request);
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'wilayah_id' => 'required|exists:wilayahs,id',
            'speciality' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'no_telp' => 'required|numeric|digits_between:11,15',
            'old_password' => 'nullable|min:6',
            'new_password' => 'nullable|confirmed|min:6',
        ]);

        $user = User::find(auth()->user()->id);
        if ($request->filled('old_password') && $request->filled('new_password')) {
            if (Hash::check($request->old_password, $user->password)) {
                $validatedData['password'] = $request->new_password;
            } else {
                return redirect()->back()->withErrors(['old_password' => 'Password lama tidak sesuai.']);
            }
        }

        $user->update($validatedData);
        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
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
