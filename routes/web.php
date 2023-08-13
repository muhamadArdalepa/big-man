<?php


use Illuminate\Support\Facades\Route;
// auth
use App\Http\Controllers\Auth\LoginController as Login;
use App\Http\Controllers\Auth\RegisterController as Register;

// storage
use App\Http\Controllers\StorageController as Storage;

// admin
use App\Http\Controllers\Api\TimController as AdminTim;
use App\Http\Controllers\Api\AbsenController as AdminAbsen;
use App\Http\Controllers\Api\LaporanController as AdminLaporan;
use App\Http\Controllers\Api\PemasanganController as AdminPemasangan;
use App\Http\Controllers\Api\TeknisiController as AdminTeknisi;
use App\Http\Controllers\Api\PelangganController as AdminPelanggan;
use App\Http\Controllers\Api\PekerjaanController as AdminPekerjaan;

// teknisi
use App\Http\Controllers\Api\TeknisiAbsenController as TeknisiAbsen;
use App\Http\Controllers\Api\TeknisiPekerjaanController as TeknisiPekerjaan;

// pelanggan
use App\Http\Controllers\Api\PelangganLaporanController as PelangganLaporan;
use App\Http\Controllers\Api\PelangganPemasanganController as PelangganPemasangan;

// pages
use App\Http\Controllers\PageController as Page;

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

// guest
Route::group(['middleware' => 'guest'], function () {
	Route::get('/register', 					[Register::class, 'create'])->name('register');
	Route::post('/register', 					[Register::class, 'store'])->name('register.perform');
	Route::get('/login', 						[Login::class, 'show'])->name('login');
	Route::post('/login', 						[Login::class, 'login'])->name('login.perform');
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
	Route::get('/dashboard', 					[Page::class, 'home'])->name('home');
	Route::get('/pekerjaan', 					[Page::class, 'pekerjaan'])->name('pekerjaan');
	Route::get('/pekerjaan/{id}', 				[Page::class, 'pekerjaan_show'])->name('pekerjaan.show');
	Route::get('/laporan', 						[Page::class, 'laporan'])->name('laporan');
	Route::get('/laporan/{id}',					[Page::class, 'laporan_show'])->name('laporan.show');
	Route::get('/pemasangan',					[Page::class, 'pemasangan'])->name('pemasangan');
	Route::get('/pemasangan/create',			[Page::class, 'pemasangan_create'])->name('pemasangan.create');
	Route::get('/tim', 							[Page::class, 'tim'])->name('tim');
	Route::get('/absen', 						[Page::class, 'absen'])->name('absen');
	Route::get('/teknisi', 						[Page::class, 'teknisi'])->name('teknisi');
	Route::get('/teknisi/{id}', 				[Page::class, 'teknisi_show'])->name('teknisi.show');
	Route::get('/pelanggan', 					[Page::class, 'pelanggan'])->name('pelanggan');
	Route::get('/profile', 						[page::class, 'auth_profile'])->name('auth.profile');


	//post
	// Route::post('/profile', 					[UserProfile::class, 'update'])->name('profile.update');
	Route::post('logout', 						[Login::class, 'logout'])->name('logout');
});


// api
Route::middleware('auth.api')->group(function () {
	// admin
	Route::resource('api/pekerjaan',			AdminPekerjaan::class)->except(['create','edit','show']);
	Route::resource('api/laporan', 				AdminLaporan::class)->except(['create','edit']);
	Route::resource('api/pemasangan',			AdminPemasangan::class)->except(['create','edit']);
	Route::post('api/pemasangan/{pemasangan}',	[AdminPemasangan::class,'update_foto']);
	Route::resource('api/tim',					AdminTim::class)->except(['create','edit']);
	Route::resource('api/absen',				AdminAbsen::class)->except(['create','edit']);

	Route::resource('api/teknisi',				AdminTeknisi::class)->except(['create','edit']);
	Route::resource('api/pelanggan',			AdminPelanggan::class)->except(['create','edit']);
	
	// teknisi
	Route::resource('api/teknisi-absen',		TeknisiAbsen::class)->except(['create','edit']);
	Route::resource('api/teknisi-pekerjaan',	TeknisiPekerjaan::class)->except(['create','edit']);
	
	
	// pelanggan
	Route::resource('api/pelanggan-laporan',	PelangganLaporan::class)->except(['create','edit']);
	Route::resource('api/pelanggan-pemasangan',	PelangganPemasangan::class)->except(['create','edit']);
	
	// select2-data
	Route::get('api/select2-laporan-pelanggan',	[AdminLaporan::class,'select2_pelanggan']);
	Route::get('api/select2-laporan-tim',		[AdminLaporan::class,'select2_tim']);
	Route::get('api/data-laporan-pelanggan/{id}',		[AdminLaporan::class,'data_pelanggan']);

	Route::get('api/select2-tim-teknisi',			[AdminTim::class,'select2_teknisi']);
});


// storage
Route::middleware(['auth.storage'])->group(function () {
	Route::get('/storage/private/{path}', Storage::class)
		->where('path', '.*')
		->name('storage.private');
});


// test page
Route::get('/test', function () {
	return view('pages.test');
});