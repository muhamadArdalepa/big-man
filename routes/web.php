<?php


use Illuminate\Support\Facades\Route;
// auth
use App\Http\Controllers\Auth\LoginController as Login;
use App\Http\Controllers\Auth\RegisterController as Register;

// storage
use App\Http\Controllers\StorageController as Storage;

// admin
use App\Http\Controllers\Admin\TimController as AdminTim;
use App\Http\Controllers\Admin\AbsenController as AdminAbsen;
use App\Http\Controllers\Admin\LaporanController as AdminLaporan;
use App\Http\Controllers\Admin\TeknisiController as AdminTeknisi;
use App\Http\Controllers\Admin\PelangganController as AdminPelanggan;
use App\Http\Controllers\Admin\PekerjaanController as AdminPekerjaan;

// teknisi
use App\Http\Controllers\Teknisi\AbsenController as TeknisiAbsen;

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
	Route::get('/laporan', 						[Page::class, 'laporan'])->name('laporan');
	Route::get('/laporan/{id}',					[Page::class, 'laporan_show'])->name('laporan.show');
	Route::get('/tim', 							[Page::class, 'tim'])->name('tim');
	Route::get('/absen', 						[Page::class, 'absen'])->name('absen');
	Route::get('/pekerjaan', 					[Page::class, 'pekerjaan'])->name('pekerjaan');
	Route::get('/teknisi', 						[Page::class, 'teknisi'])->name('teknisi');
	Route::get('/teknisi/{id}', 				[Page::class, 'teknisi_show'])->name('teknisi.show');
	Route::get('/pelanggan', 					[Page::class, 'pelanggan'])->name('pelanggan');
	Route::get('/profile', 						[page::class, 'auth_profile'])->name('auth.profile');


	//post
	Route::post('/profile', 					[UserProfile::class, 'update'])->name('profile.update');
	Route::post('logout', 						[Login::class, 'logout'])->name('logout');
});


// api
Route::middleware('auth.api')->group(function () {
	// admin
	Route::resource('api/laporan', 				AdminLaporan::class);
	Route::resource('api/tim',					AdminTim::class);
	Route::resource('api/teknisi',				AdminTeknisi::class);
	Route::resource('api/pelanggan',			AdminPelanggan::class);
	Route::resource('api/absen',				AdminAbsen::class);
	Route::resource('api/pekerjaan',			AdminPekerjaan::class);
	
	// teknisi
	Route::resource('api/teknisi-absen',		TeknisiAbsen::class);

	// select2-data
	Route::get('api/select2-laporan-pelanggan',		[AdminLaporan::class,'select2_pelanggan']);
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