<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Models\Setting;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangBMNController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangKeluarBMNController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PengajuanBMNController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\CatatanController;

use App\Http\Controllers\Dashboard2Controller;
use App\Http\Controllers\InputPemeliharaanController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PengajuanPemeliharaanController;
use App\Http\Controllers\RiwayatPajakController;
use App\Http\Controllers\RiwayatServisController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\SeksiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Setting2Controller;

$setting = Setting::first();

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
// LOGIN & LOGOUT
Route::get('/', [LoginController::class, 'index'])
    ->name('login');
// Route::get('/login', [LoginController::class, 'index'])
   // ->name('login.form');
Route::post('/login', [LoginController::class, 'authenticate'])
    ->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::middleware('auth')->group(function () use ($setting) {
    /*
    |--------------------------------------------------------------------------
    | SEMUA ROLE
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard-' . Str::slug($setting->nama_aplikasi ?? 'dashboard-singobarong'), [DashboardController::class, 'index'])
        ->name('dashboard.index');
    Route::get('/dashboard-' . Str::slug($setting->nama_aplikasi2 ?? 'dashboard-pemeliharaan'), [Dashboard2Controller::class, 'index'])
        ->name('dashboard2.index');

    // EDIT PROFILE
    Route::get('/profile/edit', [UserController::class, 'editProfile'])
        ->name('profile.edit');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])
        ->name('profile.update');

    // RIWAYAT & NOTIFIKASI
    Route::get('/pengajuan/riwayat', [PengajuanController::class, 'riwayat'])
        ->name('pengajuan.riwayat');
    /* Route::get('/pengajuan/{id}/read', [PengajuanController::class, 'read'])
        ->name('pengajuan.read'); */
    Route::get('/pengajuan-bmn/riwayat', [PengajuanBMNController::class, 'riwayat'])
        ->name('pengajuan-bmn.riwayat');
    /* Route::get('/pengajuan-bmn/{id}/read', [PengajuanBMNController::class, 'read'])
        ->name('pengajuan-bmn.read'); */

    // MODAL SHOW DATA PENGAJUAN
    Route::get('/pengajuan/{id}/detail', [PengajuanController::class, 'detail'])
        ->name('pengajuan.detail');
    Route::get('/pengajuan-bmn/{id}/detail', [PengajuanBMNController::class, 'detail'])
        ->name('pengajuan-bmn.detail');
    Route::get('/pemeliharaan/{id}/detail', [PengajuanPemeliharaanController::class, 'detail'])
        ->name('pemeliharaan.detail');

    // data barang
    Route::resource('barang', BarangController::class)
        ->only(['index']);
    Route::resource('barang-bmn', BarangBMNController::class)
        ->only(['index']);

    // route cetak surat nodin & bast
    Route::get('/pengajuan/{id}/nodin', [PengajuanController::class, 'nodin'])
        ->name('pengajuan.nodin');
    Route::get('/pengajuan/{id}/bast', [PengajuanController::class, 'bast'])
        ->name('pengajuan.bast');
    Route::get('/pengajuan-bmn/{id}/nodin', [PengajuanBMNController::class, 'nodin'])
        ->name('pengajuan-bmn.nodin');
    Route::get('/pengajuan-bmn/{id}/bast', [PengajuanBMNController::class, 'bast'])
        ->name('pengajuan-bmn.bast');
    Route::get('/pengajuan-pemeliharaan/{id}/nodin', [PengajuanPemeliharaanController::class, 'nodin'])
        ->name('pengajuan-pemeliharaan.nodin');

    /*
    |--------------------------------------------------------------------------
    | ================= PEMELIHARAAN (SEMUA ROLE) =================
    |--------------------------------------------------------------------------
    */
    Route::prefix('pemeliharaan')->name('pemeliharaan.')->group(function () {
        // data kendaraan
        Route::get('/kendaraan', [KendaraanController::class, 'index'])
            ->name('kendaraan.index');

        // Riwayat Servis
        Route::get('/riwayat-servis', [RiwayatServisController::class, 'index'])
            ->name('riwayat_servis.index');
        Route::post('/riwayat-servis', [RiwayatServisController::class, 'store'])
            ->name('riwayat_servis.store');
        Route::put('/riwayat-servis/{id}', [RiwayatServisController::class, 'update'])
            ->name('riwayat_servis.update');
        Route::delete('/riwayat-servis/{id}', [RiwayatServisController::class, 'destroy'])
            ->name('riwayat_servis.destroy');
    });

    // check nopol kendaraan
    Route::get('/check-nopol', [KendaraanController::class, 'checkNopol'])
        ->name('pemeliharaan.kendaraan.checkNopol');
});

/*
|--------------------------------------------------------------------------
| ================= ADMINISTRATOR ONLY =================
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Administrator'])->group(function () {
    /*
    | MASTER BARANG PERSEDIAAN
    */
    Route::post('/barang/import', [KendaraanController::class, 'import'])
            ->name('barang.import');
    Route::resource('barang', BarangController::class)
        ->only(['store', 'update', 'destroy']);
    Route::get('/check-kode-barang', [BarangController::class, 'checkKode'])
        ->name('barang.checkKode');
    Route::resource('kategori', KategoriController::class)
        ->only(['index','store','update','destroy']);

    /*
    | MASTER BARANG BMN
    */
    Route::prefix('barang-bmn')->name('barang-bmn.')->group(function () {
        Route::post('/', [BarangBMNController::class, 'store'])
            ->name('store');
        Route::put('/{id}', [BarangBMNController::class, 'update'])
            ->name('update');
        Route::delete('/{id}', [BarangBMNController::class, 'destroy'])
            ->name('destroy');
    });
    Route::get('/barang-bmn/check-kode', [BarangBMNController::class, 'checkKode'])
        ->name('barang-bmn.checkKode');

    /*
    | BARANG MASUK
    */
    Route::prefix('barang-masuk')->name('barang-masuk.')->group(function () {
        Route::get('/', [BarangMasukController::class, 'index'])
            ->name('index');
        Route::post('/', [BarangMasukController::class, 'store'])
            ->name('store');
        Route::delete('/{id}', [BarangMasukController::class, 'destroy'])
            ->name('destroy');
        Route::get('/{id}/detail', [BarangMasukController::class, 'detail'])
            ->name('detail');
        Route::get('/{id}/edit', [BarangMasukController::class, 'edit']);
        Route::put('/{id}', [BarangMasukController::class, 'update'])
            ->name('update');
    });

    /*
    | BARANG KELUAR
    */
    Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])
        ->name('barang-keluar.index');
    Route::get('/barang-keluar/{id}/detail', [App\Http\Controllers\BarangKeluarController::class, 'detail']);
    Route::delete('/barang-keluar/{id}', [BarangKeluarController::class, 'destroy'])
        ->name('barang-keluar.destroy');

    /*
    | BARANG KELUAR BMN
    */
    Route::get('/barang-keluar-bmn', [BarangKeluarBMNController::class, 'index'])
        ->name('barang-keluar-bmn.index');
    Route::get('/barang-keluar-bmn/{id}/detail', [App\Http\Controllers\BarangKeluarBMNController::class, 'detail']);
    Route::delete('/barang-keluar-bmn/{id}', [BarangKeluarBMNController::class, 'destroy'])
        ->name('barang-keluar-bmn.destroy');

    /*
    | RIWAYAT PENGAJUAN BARANG PERSEDIAAN (ADMINISTRATOR)
    */
    Route::get('/pengajuan/riwayat-admin', [PengajuanController::class, 'riwayat_admin'])
        ->name('pengajuan.riwayat_admin');
    /* Route::put('/pengajuan/{id}/update-status', [PengajuanController::class, 'updateStatus'])
        ->name('pengajuan.updateStatus'); */
    Route::post('/pengajuan/{id}/update-detail-status', [PengajuanController::class, 'updateDetailStatus'])
        ->name('pengajuan.updateDetailStatus');
    Route::delete('/pengajuan/{id}', [PengajuanController::class, 'destroy'])
        ->name('pengajuan.destroy');
    Route::get('/pengajuan/riwayat-admin/{id}', [PengajuanController::class, 'show_admin'])
        ->whereNumber('id')
        ->name('pengajuan.show_admin');

    /*
    | RIWAYAT PENGAJUAN BARANG BMN (ADMINISTRATOR)
    */
    Route::get('/pengajuan-bmn/riwayat-admin', [PengajuanBMNController::class, 'riwayat_admin'])
        ->name('pengajuan-bmn.riwayat_admin');
    /* Route::put('/pengajuan-bmn/{id}/update-status', [PengajuanBMNController::class, 'updateStatus'])
        ->name('pengajuan-bmn.updateStatus'); */
    Route::post('/pengajuan-bmn/{id}/update-detail-status', [PengajuanBMNController::class, 'updateDetailStatus'])
        ->name('pengajuan-bmn.updateDetailStatus');
    // fetch(`{{ route('pengajuan.updateDetailStatus', ':id') }}`.replace(':id', currentPengajuan.id), {
    Route::delete('/pengajuan-bmn/{id}', [PengajuanBMNController::class, 'destroy'])
        ->name('pengajuan-bmn.destroy');
    Route::get('/pengajuan-bmn/riwayat-admin/{id}', [PengajuanBMNController::class, 'show_admin'])
        ->whereNumber('id')
        ->name('pengajuan-bmn.show_admin');

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
    Route::get('/check-email', [UserController::class, 'checkEmail'])
        ->name('user.checkEmail');

    /*
    | SETTING WEBSITE
    */
    Route::get('/setting', [App\Http\Controllers\SettingController::class, 'index'])
        ->name('setting.index');
    Route::post('/setting/update', [App\Http\Controllers\SettingController::class, 'update'])
        ->name('setting.update');
    Route::post('/setting2/update', [Setting2Controller::class, 'update'])
        ->name('setting2.update');
    
    /*
    | CATATAN (ADMIN)
    */
    Route::middleware(['auth'])->group(function () {
        Route::get('/catatan/riwayat-admin', [CatatanController::class, 'riwayatAdmin'])
            ->name('catatan.riwayat_admin');
    });

    /*
    |--------------------------------------------------------------------------
    | ================= PEMELIHARAAN (ADMIN) =================
    |--------------------------------------------------------------------------
    */
    Route::prefix('pemeliharaan')->name('pemeliharaan.')->group(function () {
        // Input Riwayat Pajak
        Route::get('/input-pajak', [InputPemeliharaanController::class, 'index_pajak'])
            ->name('input_pajak.index');
        Route::post('/input-pajak/pajak', [RiwayatPajakController::class, 'store'])
            ->name('input_pajak.store');
            
        // Input Riwayat Servis
        Route::get('/input-servis', [InputPemeliharaanController::class, 'index_servis'])
            ->name('input_servis.index');
        Route::post('/input-servis/servis', [RiwayatServisController::class, 'store'])
            ->name('input_servis.store');

        // Kendaraan
        Route::post('/kendaraan/import', [KendaraanController::class, 'import'])
            ->name('kendaraan.import');
        Route::post('/kendaraan', [KendaraanController::class, 'store'])
            ->name('kendaraan.store');
        Route::put('/kendaraan/{id}', [KendaraanController::class, 'update'])
            ->name('kendaraan.update');
        Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy'])
            ->name('kendaraan.destroy');

        // Riwayat Pengajuan Pemeliharaan
        Route::get('/riwayat-admin', [PengajuanPemeliharaanController::class, 'riwayat_admin'])
            ->name('riwayat_admin');
        Route::delete('/riwayat-admin/{id}', [PengajuanPemeliharaanController::class, 'destroy'])
            ->name('riwayat_admin.destroy');
        Route::post('/{id}/approve', [PengajuanPemeliharaanController::class, 'approve'])
            ->name('approve');
        Route::post('/{id}/reject', [PengajuanPemeliharaanController::class, 'reject'])
            ->name('reject');
        Route::get('/riwayat-admin/{id}', [PengajuanPemeliharaanController::class, 'show_admin'])
            ->whereNumber('id')
            ->name('show_admin');

        // Riwayat Pajak
        Route::get('/riwayat-pajak', [RiwayatPajakController::class, 'index'])
            ->name('riwayat_pajak.index');
        Route::put('/riwayat-pajak/{id}', [RiwayatPajakController::class, 'update'])
            ->name('riwayat_pajak.update');
        Route::delete('/riwayat-pajak/{id}', [RiwayatPajakController::class, 'destroy'])
            ->name('riwayat_pajak.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| ================= STAFF ONLY =================
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Staff'])->group(function () {
    /*
    | RIWAYAT PENGAJUAN BARANG PERSEDIAAN (STAFF)
    */
    Route::get('/pengajuan', [PengajuanController::class, 'index'])
        ->name('pengajuan.index');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])
        ->name('pengajuan.store');
    Route::get('/pengajuan/riwayat-user', [PengajuanController::class, 'riwayat_user'])
        ->name('pengajuan.riwayat_user');
    Route::get('/pengajuan/riwayat-user/{id}', [PengajuanController::class, 'show_user'])
        ->whereNumber('id')
        ->name('pengajuan.show_user');
    Route::delete('/pengajuan/{id}/cancel', [PengajuanController::class, 'cancel'])
        ->name('pengajuan.cancel');

    /*
    | RIWAYAT PENGAJUAN BARANG BMN (STAFF)
    */
    Route::get('/pengajuan-bmn', [PengajuanBMNController::class, 'index'])
        ->name('pengajuan-bmn.index');
    Route::post('/pengajuan-bmn', [PengajuanBMNController::class, 'store'])
        ->name('pengajuan-bmn.store');
    Route::get('/pengajuan-bmn/riwayat-user', [PengajuanBMNController::class, 'riwayat_user'])
        ->name('pengajuan-bmn.riwayat_user');
    Route::get('/pengajuan-bmn/riwayat-user/{id}', [PengajuanBMNController::class, 'show_user'])
        ->whereNumber('id')
        ->name('pengajuan-bmn.show_user');
    Route::delete('/pengajuan/{id}/cancel', [PengajuanBMNController::class, 'cancel'])
        ->name('pengajuan.cancel');

    /*
    | CATATAN (STAFF)
    */
    Route::middleware(['auth'])->group(function () {
        Route::get('/catatan', [CatatanController::class, 'index'])
            ->name('catatan.index');
        Route::post('/catatan', [CatatanController::class, 'store'])
            ->name('catatan.store');
    });

    /*
    |--------------------------------------------------------------------------
    | ================= PEMELIHARAAN (STAFF) =================
    |--------------------------------------------------------------------------
    */
    // Input Pengajuan Pemeliharaan
    Route::get('/pemeliharaan', [PengajuanPemeliharaanController::class, 'index'])
        ->name('pemeliharaan.index');
    Route::post('/pemeliharaan', [PengajuanPemeliharaanController::class, 'store'])
        ->name('pemeliharaan.store');

    Route::prefix('pemeliharaan')->name('pemeliharaan.')->group(function () {
        // Riwayat Pengajuan Pemeliharaan
        Route::get('/riwayat-user', [PengajuanPemeliharaanController::class, 'riwayat_user'])
            ->name('riwayat_user');
        Route::get('/riwayat-user/{id}', [PengajuanPemeliharaanController::class, 'show_user'])
            ->whereNumber('id')
            ->name('show_user');
        Route::delete('/{id}/cancel', [PengajuanPemeliharaanController::class, 'cancel'])
            ->name('cancel');
    });
});
