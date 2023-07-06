<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ChangePassword;            
            

Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/pekerjaan', [PekerjaanController::class, 'index'])->name('pekerjaan');
	Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
	Route::get('/absen', [AbsenController::class, 'index'])->name('absen');
	Route::get('/kelola-teknisi', [TeknisiController::class, 'index'])->name('kelola-teknisi');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});