<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelolaAdminController;
use App\Http\Controllers\KelolaStafController;
use App\Http\Controllers\KlasifikasiSuratController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SuratDisposisiController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\TambahStafController;
use App\Http\Controllers\VerifikasiSuratController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login')); // Halaman route '/' mengarah paksa ke login
Route::get('/login', [AuthController::class, 'login'])
    ->name('login'); // Menampilkan halaman login
Route::post('/login-post', [AuthController::class, 'authenticate'])
    ->name('login.post'); // Melakukan proses autentikasi
Route::get('/register', [AuthController::class, 'register'])
    ->name('register'); // Menampilkan halaman login
Route::post('/register-post', [AuthController::class, 'store'])
    ->name('register.post'); // melakukan proses registrasi

// Middleware auth untuk hanya pengguna yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout'); // Melakukan proses logout

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard'); // Menampilkan halaman dashboard
    Route::prefix('transaksi-surat')->group(function () {
        Route::resource('surat-masuk', SuratMasukController::class); // Menampilkan halaman surat masuk
        Route::resource('surat-keluar', SuratKeluarController::class); // Menampilkan halaman surat keluar
        Route::resource('surat-disposisi', SuratDisposisiController::class); // Menampilkan halaman surat disposisi
    });
    Route::middleware('admin')->group(function () {
        Route::resource('klasifikasi-surat', KlasifikasiSuratController::class); // Menampilkan halaman klasifikasi surat
        // Route::resource('kelola-staf', KelolaStafController::class); // Menampilkan halaman tambah staf 
        Route::resource('kelola-admin', KelolaAdminController::class); // Menampilkan halaman tambah admin
    });
    Route::middleware('kades')->group(function () {
        Route::resource('verifikasi-surat', VerifikasiSuratController::class); // Menampilkan halaman verifikasi surat
    });

    Route::get('profil', [ProfilController::class, 'index'])->name('profil.index'); // Menampilkan halaman profil
    Route::post('profil/perbarui-akun', [ProfilController::class, 'perbaruiAkun'])->name('profil.perbaruiAkun'); // Menampilkan halaman profil
});
