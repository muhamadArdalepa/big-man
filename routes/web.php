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
use App\Http\Controllers\TimController;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserProfileController;

// guest
Route::group(['middleware' => 'guest'], function () {
	Route::get('/register', 					[RegisterController::class, 'create'])->name('register');
	Route::post('/register', 					[RegisterController::class, 'store'])->name('register.perform');
	Route::get('/login', 						[LoginController::class, 'show'])->name('login');
	Route::post('/login', 						[LoginController::class, 'login'])->name('login.perform');
	Route::get('/reset-password', 				[ResetPassword::class, 'show'])->name('reset-password');
	Route::post('/reset-password', 				[ResetPassword::class, 'send'])->name('reset.perform');
	Route::get('/change-password', 				[ChangePassword::class, 'show'])->name('change-password');
	Route::post('/change-password', 			[ChangePassword::class, 'update'])->name('change.perform');
});

// auth
Route::group(['middleware' => 'auth'], function () {
	// get
	Route::get('/', function () {
		return redirect(route('home'));
	});
	Route::get('/home', function () {
		return redirect(route('home'));
	});
	// pages
	Route::get('/dashboard', 					[PageController::class, 'home'])->name('home');
	Route::get('/laporan', 						[PageController::class, 'laporan'])->name('laporan');
	Route::get('/laporan/{id}',					[PageController::class, 'laporan_show'])->name('laporan.show');
	Route::get('/tim', 							[PageController::class, 'tim'])->name('tim');
	Route::get('/absen', 						[PageController::class, 'absen'])->name('absen');
	Route::get('/pekerjaan', 					[PageController::class, 'pekerjaan'])->name('pekerjaan');
	Route::get('/teknisi', 						[PageController::class, 'teknisi'])->name('teknisi');
	Route::get('/pelanggan', 					[PageController::class, 'pelanggan'])->name('pelanggan');
	Route::get('/teknisi/{id}', 				[PageController::class, 'teknisi_show'])->name('teknisi.show');

	// profile
	Route::get('/profile', 						[ProfileController::class, 'index'])->name('profile');
	Route::get('/profile', 						[ProfileController::class, 'show'])->name('profile');

	//post
	Route::post('/profile', 					[UserProfileController::class, 'update'])->name('profile.update');
	Route::post('logout', 						[LoginController::class, 'logout'])->name('logout');
});


// api
Route::middleware('auth.api')->group(function () {
	Route::resource('api/laporan', 				LaporanController::class);
	Route::resource('api/tim',					TimController::class);
	Route::resource('api/teknisi',				TeknisiController::class);
	Route::resource('api/pelanggan',			PelangganController::class);
	
	// select2-data
	Route::get('api/select2-laporan-pelanggan',		[LaporanController::class,'select2_pelanggan']);
});


// storage
Route::middleware(['auth.storage'])->group(function () {
	Route::get('/storage/private/{path}', StorageController::class)
		->where('path', '.*')
		->name('storage.private');
});
