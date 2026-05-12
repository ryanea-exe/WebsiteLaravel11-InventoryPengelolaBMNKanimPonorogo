<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeksiController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
// LOGIN & LOGOUT
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::get('/login', [LoginController::class, 'index'])->name('login.form');
Route::post('/login', [LoginController::class, 'authenticate'])
    ->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

// EDIT PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [UserController::class, 'editProfile'])
        ->name('profile.edit');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])
        ->name('profile.update');
    
    // modal show data pengajuan
    Route::get('/pengajuan/{id}/detail', [PengajuanController::class, 'detail'])
        ->name('pengajuan.detail');
});

// RIWAYAT & NOTIFIKASI
Route::get('/pengajuan/riwayat', [PengajuanController::class, 'riwayat'])
    ->name('pengajuan.riwayat');
Route::get('/pengajuan/{id}/read', [PengajuanController::class, 'read'])
    ->name('pengajuan.read');

/*
|--------------------------------------------------------------------------
| DASHBOARD (SEMUA ROLE)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard.index');

// route cetak surat nodin & bast
Route::get('/pengajuan/{id}/nodin', [PengajuanController::class, 'nodin'])
    ->name('pengajuan.nodin');
Route::get('/pengajuan/{id}/bast', [PengajuanController::class, 'bast'])
    ->name('pengajuan.bast');

/*
|--------------------------------------------------------------------------
| ================= ADMINISTRATOR ONLY =================
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Administrator'])->group(function () {
    /*
    | MASTER BARANG
    */
    Route::resource('barang', BarangController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    Route::resource('kategori', KategoriController::class)
        ->only(['index','store','update','destroy']);

    /*
    | BARANG MASUK
    */
    Route::prefix('barang-masuk')->name('barang-masuk.')->group(function () {
        Route::get('/', [BarangMasukController::class, 'index'])->name('index');
        Route::post('/', [BarangMasukController::class, 'store'])->name('store');
        Route::delete('/barang-masuk/{id}', [BarangMasukController::class, 'destroy'])->name('destroy');
    });

    /*
    | BARANG KELUAR
    */
    Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])
        ->name('barang-keluar.index');
    Route::get(
        '/barang-keluar/{id}/detail',
        [App\Http\Controllers\BarangKeluarController::class, 'detail']
    );
    Route::delete('/barang-keluar/{id}', [BarangKeluarController::class, 'destroy'])
        ->name('barang-keluar.destroy');

    /*
    | RIWAYAT PENGAJUAN BARANG (ADMINISTRATOR)
    */
    Route::get('/pengajuan/riwayat_admin', [PengajuanController::class, 'riwayat_admin'])
        ->name('pengajuan.riwayat_admin');
    Route::put('/pengajuan/{id}/status', [PengajuanController::class, 'updateStatus'])
        ->name('pengajuan.updateStatus');
    Route::delete('/pengajuan/{id}', [PengajuanController::class, 'destroy'])
        ->name('pengajuan.destroy');
    Route::get('/pengajuan/{id}', [PengajuanController::class, 'show'])
        ->whereNumber('id')
        ->name('pengajuan.show');

    /*
    | LAPORAN
    */
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');
    Route::get('/laporan/generate', [LaporanController::class, 'generate'])
        ->name('laporan.generate');
    Route::get('/laporan/detail', [LaporanController::class, 'detail'])
        ->name('laporan.detail');
    Route::get('/laporan/print', [LaporanController::class, 'print'])
        ->name('laporan.print');

    /*
    | USERS & SEKSI
    */
    Route::resource('user', UserController::class);
    Route::resource('seksi', SeksiController::class)
        ->only(['index','store','update','destroy']);

    /*
    | SETTING WEBSITE
    */
    Route::get('/setting', [App\Http\Controllers\SettingController::class, 'index'])
            ->name('setting.index');
    Route::post('/setting/update', [App\Http\Controllers\SettingController::class, 'update'])
        ->name('setting.update');
});

/*
|--------------------------------------------------------------------------
| ================= STAFF ONLY =================
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Staff'])->group(function () {
    /*
    | RIWAYAT PENGAJUAN BARANG (STAFF)
    */
    Route::get('/pengajuan', [PengajuanController::class, 'index'])
        ->name('pengajuan.index');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])
        ->name('pengajuan.store');
    Route::get('/pengajuan/riwayat_user', [PengajuanController::class, 'riwayat_user'])
        ->name('pengajuan.riwayat_user');
});
