<?php

namespace App\Http\Controllers\Auth;

// use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function create()
    {
        $wilayahs = \App\Models\wilayah::all();
        return view('auth.register', compact('wilayahs'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'wilayah_id' => 'required',
            'no_telp' => 'required|numeric|digits_between:11,15',
            'terms' => 'required'
        ]);

        $validatedData['role'] = 3;
        $validatedData['foto_profil'] = 3;
        $user = User::create($validatedData);
        auth()->login($user);
        event(new Registered($user));
    }
}
