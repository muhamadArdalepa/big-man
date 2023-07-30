<?php

namespace App\Http\Controllers\Auth;

// use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function create()
    {
        $kotas = \App\Models\Kota::all();
        return view('auth.register',compact('kotas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'kota_id' => 'required',
            'no_telp' => 'required|numeric|digits_between:11,15',
            'terms' => 'required'
        ], [
            'nama.required' => 'Nama harus diisi.',
            'nama.min' => 'Nama harus memiliki minimal 3 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password harus memiliki minimal 6 karakter.',
            'password.confirmed' => 'Password dan konfirmasi password harus sesuai.',
            'kota_id.required' => 'Kota harus dipilih.',
            'no_telp.required' => 'Nomor telepon harus diisi.',
            'no_telp.numeric' => 'Nomor telepon harus berupa angka.',
            'no_telp.digits_between' => 'Nomor telepon memiliki minimal 11 karakter.',
            'terms.required' => 'Anda harus menyetujui Syarat dan Ketentuan untuk melanjutkan.'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $user = new User;
            $user->nama = ucwords(trim($request->nama));
            $user->role = 3;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->kota_id = $request->kota_id;
            $user->no_telp = $request->no_telp;
            $user->foto_profil = 'dummy.png';
            $user->save();
            auth()->login($user);
            return redirect('/dashboard')->with('success','Selamat bergabung di Big Net');
        }
    }
}
