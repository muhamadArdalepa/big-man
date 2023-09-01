<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

// teknisi
use App\Http\Controllers\Api\TimController;

// pelanggan
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorageController;

// apis
use App\Http\Controllers\Api\AbsenController;
use App\Http\Controllers\Api\PaketController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\TeknisiController;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PekerjaanController;
use App\Http\Controllers\Api\PelangganController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\PemasanganController;
use App\Http\Controllers\Api\PelangganLaporanController;
use App\Http\Controllers\Api\TeknisiPekerjaanController;
use App\Http\Controllers\Api\PelangganPemasanganController;


// guest
Route::group(['middleware' => 'guest'], function () {
	Route::get('/register', 					[RegisterController::class, 'create'])->name('register');
	Route::post('/register', 					[RegisterController::class, 'store'])->name('register.perform');
	Route::get('/verify', 						[RegisterController::class, 'verify'])->name('verify');
	Route::post('/verify', 						[RegisterController::class, 'verifySent'])->name('verify-sent');
	Route::get('/verify-proses',				[RegisterController::class, 'verifyProses'])->name('verify-proses');
	Route::get('/verify-email', 				[RegisterController::class, 'verifyEmail'])->name('verify-email');
	Route::get('/login', 						[LoginController::class, 'show'])->name('login');
	Route::post('/login', 						[LoginController::class, 'login'])->name('login.perform');
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
	Route::get('/pekerjaan', 					[PageController::class, 'pekerjaan'])->name('pekerjaan');
	Route::get('/pekerjaan/{id}', 				[PageController::class, 'pekerjaan_show'])->name('pekerjaan.show');
	Route::get('/laporan', 						[PageController::class, 'laporan'])->name('laporan');
	Route::get('/laporan/{id}',					[PageController::class, 'laporan_show'])->name('laporan.show');
	Route::get('/pemasangan',					[PageController::class, 'pemasangan'])->name('pemasangan');
	Route::get('/pemasangan/{id}',				[PageController::class, 'pemasangan_show'])->name('pemasangan.show');
	Route::get('/pemasangan/create',			[PageController::class, 'pemasangan_create'])->name('pemasangan.create');
	Route::get('/tim', 							[PageController::class, 'tim'])->name('tim');
	Route::get('/absen', 						[PageController::class, 'absen'])->name('absen');
	Route::get('/teknisi', 						[PageController::class, 'teknisi'])->name('teknisi');
	Route::get('/teknisi/{id}', 				[PageController::class, 'teknisi_show'])->name('teknisi.show');
	Route::get('/pelanggan', 					[PageController::class, 'pelanggan'])->name('pelanggan');
	Route::get('/wilayah', 						[PageController::class, 'wilayah'])->name('wilayah');
	Route::get('/paket', 						[PageController::class, 'paket'])->name('paket');
	Route::resource('/profile', 				ProfileController::class);


	//post
	// Route::post('/profile', 					[UserProfileController::class, 'update'])->name('profile.update');
	Route::post('logout', 						[LoginController::class, 'logout'])->name('logout');
});


// api
Route::middleware('auth.api')->group(function () {
	Route::get('api/laporan/data-pelanggan/{id}',		[LaporanController::class, 'data_pelanggan']);
	Route::get('api/laporan/select2-pelanggan',			[LaporanController::class, 'select2_pelanggan']);
	Route::get('api/pekerjaan/select2-pemasangan',		[PekerjaanController::class, 'select2_pemasangan']);
	Route::get('api/pekerjaan/select2-tim',				[PekerjaanController::class, 'select2_tim']);



	Route::resource('api/dashboard',					DashboardController::class)->except(['create', 'edit', 'show']);
	Route::resource('api/pekerjaan',					PekerjaanController::class)->except(['create', 'edit', 'show']);
	Route::resource('api/laporan', 						LaporanController::class)->except(['create', 'edit']);

	Route::get('api/pemasangan/data-pelanggan',			[PemasanganController::class, 'data_pelanggan']);
	Route::post('api/pemasangan/{pemasangan}',			[PemasanganController::class, 'update_foto']);
	Route::resource('api/pemasangan',					PemasanganController::class)->except(['create', 'edit']);
	Route::resource('api/tim',							TimController::class)->except(['create', 'edit']);
	Route::resource('api/absen',						AbsenController::class)->except(['create', 'edit']);

	Route::resource('api/teknisi',						TeknisiController::class)->except(['create', 'edit']);
	Route::resource('api/pelanggan',					PelangganController::class)->except(['create', 'edit']);
	Route::resource('api/wilayah',						WilayahController::class)->except(['create', 'edit']);
	Route::resource('api/paket',						PaketController::class)->except(['create', 'edit']);

	// teknisi
	Route::resource('api/teknisi-pekerjaan',			TeknisiPekerjaanController::class)->except(['create', 'edit']);


	// pelanggan
	Route::resource('api/pelanggan-laporan',			PelangganLaporanController::class)->except(['create', 'edit']);
	Route::resource('api/pelanggan-pemasangan',			PelangganPemasanganController::class)->except(['create', 'edit']);

	// select2-data	
	Route::get('api/select2-laporan-tim',				[LaporanController::class, 'select2_tim']);
	Route::get('api/select2-tim-teknisi',				[TimController::class, 'select2_teknisi']);
});


// storage
Route::middleware(['auth.storage'])->group(function () {
	Route::get('/storage/private/{path}', StorageController::class)
		->where('path', '.*')
		->name('storage.private');
});
