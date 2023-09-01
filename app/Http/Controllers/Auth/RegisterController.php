<?php

namespace App\Http\Controllers\Auth;

// use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Mail\MailNotify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VerifyEmailUser;
use Illuminate\Support\Facades\Mail;
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
        $validatedData['foto_profil'] = 'profile/dummy.png';
        $user = User::create($validatedData);
        session(['registered_email'=>$user->email]);
        session(['registered_id'=>$user->id]);

        return redirect(route('verify'));
    }

    public function verify()
    {
        return view('auth.verify');
    }
    public function verifySent(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,'.$request->id,
        ]);
        VerifyEmailUser::where('user_id',$request->id)->delete();
        $token = new VerifyEmailUser();
        $token->user_id = User::findOrFail($request->id)->id;
        $token->token = bcrypt($request->email);
        $token->save();
        $email = new MailNotify($token->token);
        Mail::to($request->email)->send(new $email($token->token));
        User::findOrFail($request->id)->update(['email'=>$request->email]);
        session(['registered_email'=>$request->email]);
        return redirect(route('verify-proses'));
    }
    public function verifyProses()
    {
        return view('auth.verify-proses');
    }
    public function verifyEmail(Request $request)
    {
        $user_id = VerifyEmailUser::where('token',$request->token)->first()->user_id;
        User::findOrFail($user_id)->update(['email_verified_at'=>now()]);
        VerifyEmailUser::where('user_id',$user_id)->delete();
        return view('auth.verify-success');
    }

}   
