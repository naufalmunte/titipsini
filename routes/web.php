<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboard;
use App\Http\Controllers\Finance\DashboardController as FinanceDashboard;
use App\Http\Controllers\Pelanggan\VerifyController as PelangganVerify;
use App\Http\Controllers\Mitra\VerifyController as MitraVerify;

use App\Http\Controllers\Admin\VerifyAdminController;
use App\Http\Controllers\Pelanggan\AlamatController;
use App\Http\Controllers\Mitra\ProfilController;
use App\Http\Controllers\Mitra\JamOperasionalController;
use App\Http\Controllers\Mitra\GudangController;
use App\Http\Controllers\Admin\GudangAdminController;
use App\Http\Controllers\Mitra\LahanParkirController;
use App\Http\Controllers\Admin\LahanParkirAdminController;
use App\Http\Controllers\Mitra\PenjagaBangunanController;
use App\Http\Controllers\Admin\VerifikasiPenjagaController;

use App\Http\Controllers\Admin\JenisArmadaController;

use App\Http\Controllers\Admin\ArmadaController as AdminArmada;
use App\Http\Controllers\Mitra\ArmadaMitraController as MitraArmada;

use App\Http\Controllers\Pelanggan\BookingController;

use App\Http\Controllers\Finance\OrderController as FinanceOrder;
use App\Http\Controllers\Mitra\BarangMasukController;

use App\Http\Controllers\Pelanggan\OrderGudangController as PelangganOrderGudang;
use App\Http\Controllers\Admin\OrderGudangController as AdminOrderGudang;

use App\Http\Controllers\Pelanggan\OrderAngkutanController;
use App\Http\Controllers\Admin\OrderAngkutanController as AdminAngkutan;
use App\Http\Controllers\Finance\AngkutanController as FinanceAngkutan;

use App\Http\Controllers\Mitra\AngkutanMasukController;

use App\Http\Controllers\Finance\OrderKendaraanController as FinanceOrderKendaraan;
use App\Http\Controllers\Pelanggan\OrderKendaraanController as PelangganOrderKendaraan;
use App\Http\Controllers\Admin\OrderKendaraanController as AdminKendaraan;

use App\Http\Controllers\Mitra\KendaraanMasukController;
use App\Http\Controllers\Admin\KategoriBangunanController;

use App\Http\Controllers\Pelanggan\OrderBangunanController;

use App\Http\Controllers\Mitra\BangunanMasukController;

use App\Http\Controllers\Mitra\RekeningController;
use App\Http\Controllers\Mitra\LaporanController as MitraLaporan;

use App\Http\Controllers\LaporanController as FinanceLaporan;
use App\Http\Controllers\Mitra\WithdrawalController;

use App\Http\Controllers\Admin\MitraController;

use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\KategoriKendaraanController;
use App\Http\Controllers\Admin\KategoriBarangController;




/*  
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

use App\Http\Controllers\LandingPageController;

// JEDER! Jangan langsung return view, tapi panggil Controller
Route::get('/', [LandingPageController::class, 'index'])->name('welcome');
/*
|--------------------------------------------------------------------------
| Dashboard Redirect (berdasarkan role)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return 'Redirecting...';
})->middleware(['auth', RoleMiddleware::class])->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', RoleMiddleware::class])->group(function () {
Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
    Route::get('/user', [UserController::class, 'index'])->name('admin.user');
    Route::post('/user', [UserController::class, 'store'])->name('admin.user.store');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');

    Route::get('/verifikasi', [VerifyAdminController::class, 'index'])->name('admin.verify.index');
    Route::patch('/verifikasi/{id}', [VerifyAdminController::class, 'updateStatus'])->name('admin.verify.update');

    Route::get('/gudang', [GudangAdminController::class, 'index'])->name('admin.gudang.index');
    Route::put('/gudang/{id}/verify', [GudangAdminController::class, 'verify'])->name('admin.gudang.verify');

    Route::get('/parkir', [LahanParkirAdminController::class, 'index'])->name('admin.parkir.index');
    Route::put('/parkir/{id}/verify', [LahanParkirAdminController::class, 'verify'])->name('admin.parkir.verify');

    Route::get('/verifikasi-penjaga', [VerifikasiPenjagaController::class, 'index'])->name('admin.verifikasi-penjaga.index');
    Route::patch('/verifikasi-penjaga/{id}/approve', [VerifikasiPenjagaController::class, 'approve'])->name('admin.verifikasi-penjaga.approve');
    Route::patch('/verifikasi-penjaga/{id}/reject', [VerifikasiPenjagaController::class, 'reject'])->name('admin.verifikasi-penjaga.reject');

    Route::get('/jenis-armada', [JenisArmadaController::class, 'index'])->name('admin.jenis-armada.index');
    Route::post('/jenis-armada', [JenisArmadaController::class, 'store'])->name('admin.jenis-armada.store');
    Route::put('/jenis-armada/{id}', [JenisArmadaController::class, 'update'])->name('admin.jenis-armada.update');
    Route::delete('/jenis-armada/{id}', [JenisArmadaController::class, 'destroy'])->name('admin.jenis-armada.destroy');

    // Rute Armada (Duplikat sudah dihapus)
    Route::get('/armada', [AdminArmada::class, 'index'])->name('admin.armada.index');
    Route::post('/armada/{id}/verifikasi', [AdminArmada::class, 'verifikasi'])->name('admin.armada.verifikasi');

    Route::get('/order-gudang', [AdminOrderGudang::class, 'index'])->name('admin.order-gudang');
    Route::post('/order-gudang/{id}/confirm', [AdminOrderGudang::class, 'confirm'])->name('admin.order-gudang.confirm');
    Route::post('/order-gudang/{id}/tolak', [AdminOrderGudang::class, 'tolak'])->name('admin.order-gudang.tolak');

    Route::get('/order-angkutan', [AdminAngkutan::class, 'index'])->name('admin.order_angkutan.index');
    Route::post('/order-angkutan/konfirmasi/{id}', [AdminAngkutan::class, 'konfirmasi'])->name('admin.order_angkutan.konfirmasi');
    Route::post('/order-angkutan/tolak/{id}', [AdminAngkutan::class, 'tolak'])->name('admin.order_angkutan.tolak');

    Route::get('/order-kendaraan', [AdminKendaraan::class, 'index'])->name('admin.order_kendaraan.index');
    Route::post('/order-kendaraan/konfirmasi/{id}', [AdminKendaraan::class, 'konfirmasi'])->name('admin.order_kendaraan.konfirmasi');
    Route::post('/order-kendaraan/tolak/{id}', [AdminKendaraan::class, 'tolak'])->name('admin.order_kendaraan.tolak');

    Route::get('order-bangunan', [App\Http\Controllers\Admin\OrderBangunanController::class, 'index'])->name('admin.order-bangunan.index');
    Route::post('order-bangunan/{id}/konfirmasi', [App\Http\Controllers\Admin\OrderBangunanController::class, 'konfirmasi'])->name('admin.order-bangunan.konfirmasi');
    Route::post('order-bangunan/{id}/tolak', [App\Http\Controllers\Admin\OrderBangunanController::class, 'tolak'])->name('admin.order-bangunan.tolak');

    Route::resource('kategori-bangunan', KategoriBangunanController::class)->names('admin.kategori-bangunan');

    Route::get('/komisi', [App\Http\Controllers\Admin\KomisiController::class, 'index'])->name('admin.komisi.index');
    Route::put('/komisi/update', [App\Http\Controllers\Admin\KomisiController::class, 'update'])->name('admin.komisi.update');

    Route::get('/mitra-aktif', [MitraController::class, 'index'])->name('admin.mitra.index');

    Route::get('/pengaturan-global', [SettingController::class, 'index'])->name('admin.pengaturan');
    Route::put('/pengaturan-global/update', [SettingController::class, 'update'])->name('admin.pengaturan.update');
    Route::resource('/kategori-barang', KategoriBarangController::class, ['as' => 'admin']);

    Route::resource('/kategori-kendaraan', KategoriKendaraanController::class, ['as' => 'admin']);
});





/*
|--------------------------------------------------------------------------
| MITRA
|--------------------------------------------------------------------------
*/
Route::prefix('mitra')->middleware(['auth', RoleMiddleware::class])->group(function () {
    
    // // 1. Dashboard (Otomatis menyesuaikan tampilan: form verify, form profil, atau gudang)
    // Route::get('/dashboard', function () {
    //     return view('mitra.dashboard');
    // })->name('mitra.dashboard');

    Route::get('/dashboard', [App\Http\Controllers\Mitra\DashboardController::class, 'index'])->name('mitra.dashboard');

    // 2. Proses Verifikasi KTP
    Route::get('/verify', [MitraVerify::class, 'index'])->name('mitra.verify.index');
    Route::post('/verify', [MitraVerify::class, 'store'])->name('mitra.verify.store');

    // 3. Proses Simpan Profil PT (Dipanggil dari form di dashboard)
    Route::get('/profil/pengaturan', [ProfilController::class, 'edit'])->name('mitra.profil.edit');
    Route::post('/profil/simpan', [ProfilController::class, 'store'])->name('mitra.profil.store');


        // Rute Jam Operasional (Sudah diarahkan ke Controller baru)        
    Route::get('/pengaturan/jam-operasional', [JamOperasionalController::class, 'index'])->name('mitra.jam.index');
    Route::post('/pengaturan/jam-operasional', [JamOperasionalController::class, 'update'])->name('mitra.jam.update');

    Route::get('/gudang', [GudangController::class, 'index'])->name('mitra.gudang.index');
    Route::post('/gudang/simpan', [GudangController::class, 'store'])->name('mitra.gudang.store');
    Route::put('/gudang/{id}', [GudangController::class, 'update'])->name('mitra.gudang.update');
    Route::delete('/gudang/{id}', [GudangController::class, 'destroy'])->name('mitra.gudang.destroy');
    Route::patch('/gudang/{id}/toggle', [GudangController::class, 'toggleStatus'])->name('mitra.gudang.toggle');

    Route::get('/parkir', [LahanParkirController::class, 'index'])->name('mitra.parkir.index');
    Route::post('/parkir/simpan', [LahanParkirController::class, 'store'])->name('mitra.parkir.store');
    Route::put('/parkir/{id}', [LahanParkirController::class, 'update'])->name('mitra.parkir.update');
    Route::patch('/parkir/{id}/toggle', [LahanParkirController::class, 'toggleStatus'])->name('mitra.parkir.toggle');
    Route::delete('/parkir/{id}', [LahanParkirController::class, 'destroy'])->name('mitra.parkir.destroy');

    Route::get('/penjaga', [PenjagaBangunanController::class, 'index'])->name('mitra.penjaga.index');
    Route::post('/penjaga', [PenjagaBangunanController::class, 'store'])->name('mitra.penjaga.store');
    Route::delete('/penjaga/{id}', [PenjagaBangunanController::class, 'destroy'])->name('mitra.penjaga.destroy');
    Route::put('/penjaga/{id}', [PenjagaBangunanController::class, 'update'])->name('mitra.penjaga.update');

    Route::get('/armada', [MitraArmada::class, 'index'])->name('mitra.armada.index');
    Route::post('/armada', [MitraArmada::class, 'store'])->name('mitra.armada.store');
    Route::put('/armada/{id}', [MitraArmada::class, 'update'])->name('mitra.armada.update');
    Route::delete('/armada/{id}', [MitraArmada::class, 'destroy'])->name('mitra.armada.destroy');   
    
    Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('mitra.barang-masuk.index');
    Route::post('/barang-masuk/{id}/konfirmasi', [BarangMasukController::class, 'konfirmasi'])->name('mitra.barang-masuk.konfirmasi');
    Route::post('/barang-masuk/checkout/{id}', [BarangMasukController::class, 'checkout'])->name('mitra.barang-masuk.checkout');

    Route::get('/angkutan-masuk', [AngkutanMasukController::class, 'index'])->name('mitra.angkutan-masuk.index');
    Route::post('/angkutan-masuk/{id}/jemput', [AngkutanMasukController::class, 'konfirmasiJemput'])->name('mitra.angkutan-masuk.jemput');
    Route::post('/angkutan-masuk/{id}/selesai', [AngkutanMasukController::class, 'konfirmasiSelesai'])->name('mitra.angkutan-masuk.selesai');
    
    Route::get('/kendaraan-masuk', [KendaraanMasukController::class, 'index'])->name('kendaraan.masuk.index');
    Route::post('/kendaraan-masuk/{id}/konfirmasi', [KendaraanMasukController::class, 'konfirmasi'])->name('kendaraan.masuk.konfirmasi');
    Route::post('/kendaraan/checkout/{id}', [KendaraanMasukController::class, 'checkout'])->name('kendaraan.checkout');

    Route::get('/bangunan-masuk', [BangunanMasukController::class, 'index'])->name('mitra.bangunan-masuk.index');
    Route::post('/bangunan-masuk/{id}/assign', [BangunanMasukController::class, 'assign'])->name('mitra.bangunan-masuk.assign');
    Route::post('/bangunan-masuk/{id}/selesai', [BangunanMasukController::class, 'selesai'])->name('mitra.bangunan-masuk.selesai');

    Route::get('/rekening', [RekeningController::class, 'index'])->name('mitra.rekening.index');
    Route::post('/rekening', [RekeningController::class, 'store'])->name('mitra.rekening.store');
    Route::delete('/rekening/{id}', [RekeningController::class, 'destroy'])->name('mitra.rekening.destroy');
    Route::put('/rekening/{id}', [RekeningController::class, 'update'])->name('mitra.rekening.update');

    Route::get('/laporan-pendapatan', [MitraLaporan::class, 'indexLaporanMitra'])
        ->name('mitra.laporan.index');

    Route::post('/withdrawal', [WithdrawalController::class, 'store'])->name('mitra.withdrawal.store');

    // Letakkan di dalam group route mitra
Route::put('/mitra/withdrawal/konfirmasi/{id}', [App\Http\Controllers\Mitra\WithdrawalController::class, 'konfirmasiSelesai'])->name('mitra.withdrawal.konfirmasi');
     
     
        });





/*
|--------------------------------------------------------------------------
| FINANCE
|--------------------------------------------------------------------------
*/
Route::prefix('finance')->name('finance.')->middleware(['auth', RoleMiddleware::class])->group(function () {
    
Route::get('/dashboard', [FinanceDashboard::class, 'index'])->name('dashboard');
    Route::get('/confirm', [FinanceOrder::class, 'index'])->name('confirm.index');
    Route::get('/confirm/{id}/detail', [FinanceOrder::class, 'show'])->name('confirm.show');
    Route::post('/confirm/{id}/approve', [FinanceOrder::class, 'approve'])->name('order.approve');

    Route::get('/confirm-angkutan', [FinanceAngkutan::class, 'index'])->name('confirm-angkutan.index');
    Route::get('/confirm-angkutan/{id}', [FinanceAngkutan::class, 'show'])->name('confirm-angkutan.show');
    Route::post('/confirm-angkutan/{id}/approve', [FinanceAngkutan::class, 'approve'])->name('confirm-angkutan.approve');

    Route::get('/confirm-kendaraan', [FinanceOrderKendaraan::class, 'index'])->name('confirm-kendaraan.index');
    Route::get('/confirm-kendaraan/{id}', [FinanceOrderKendaraan::class, 'show'])->name('confirm-kendaraan.show');
    Route::post('/confirm-kendaraan/{id}/approve', [FinanceOrderKendaraan::class, 'approve'])->name('confirm-kendaraan.approve');  
    
    Route::get('/confirm-kendaraan', [FinanceOrderKendaraan::class, 'index'])->name('confirm-kendaraan.index');
    Route::get('/confirm-kendaraan/{id}', [FinanceOrderKendaraan::class, 'show'])->name('kendaraan-detail');
    Route::post('/confirm-kendaraan/{id}/approve', [FinanceOrderKendaraan::class, 'approve'])->name('confirm-kendaraan.approve');

    // Rute Laporan Keuangan Finance
Route::get('/laporan-keuangan', [\App\Http\Controllers\Finance\LaporanController::class, 'index'])->name('laporan.index');

Route::get('order-bangunan', [App\Http\Controllers\Finance\OrderBangunanController::class, 'index'])
        ->name('order-bangunan.index');

    // Detail & Verifikasi (INI YANG TADI ERROR)
    // Kita beri nama 'bangunan-detail' agar sesuai dengan panggilan di Blade
    Route::get('order-bangunan/{id}', [App\Http\Controllers\Finance\OrderBangunanController::class, 'show'])
        ->name('bangunan-detail'); 

   // Ganti baris approve menjadi seperti ini:
Route::post('order-bangunan/{id}/approve', [App\Http\Controllers\Finance\OrderBangunanController::class, 'approve'])
    ->name('order-bangunan.approve'); // <--- Nama disamakan dengan Blade

    
    // Route untuk konfirmasi transfer ke mitra
Route::post('/laporan-keuangan/{id}/payout', [LaporanController::class, 'konfirmasiPayout'])
        ->name('laporan.payout');

Route::get('/withdrawal', [FinanceLaporan::class, 'indexWithdrawal'])->name('withdrawal.index');
    Route::put('/withdrawal/{id}', [FinanceLaporan::class, 'updateStatus'])->name('withdrawal.update');
    
    });

/*
|--------------------------------------------------------------------------
| PELANGGAN
|--------------------------------------------------------------------------
*/

Route::prefix('pelanggan')->middleware(['auth', RoleMiddleware::class])->group(function () {
    
    // Dashboard (Bisa dibuka kapan saja sesuai permintaanmu)
Route::get('/dashboard', [PelangganDashboard::class, 'index'])->name('pelanggan.dashboard');
    // Route Verifikasi (Harus di sini agar tidak kena tendang middleware EnsureVerified)
    Route::get('/verify', [PelangganVerify::class, 'index'])->name('pelanggan.verify.index');
    Route::post('/verify', [PelangganVerify::class, 'store'])->name('pelanggan.verify.store');
});

Route::prefix('pelanggan')->middleware(['auth', RoleMiddleware::class, \App\Http\Middleware\EnsureVerified::class])->group(function () {    
    // Dashboard bisa dibuka kapan saja
   
    Route::get('/profil', [App\Http\Controllers\Pelanggan\ProfilPelangganController::class, 'index'])->name('pelanggan.profil.index');
    Route::post('/profil', [App\Http\Controllers\Pelanggan\ProfilPelangganController::class, 'store'])->name('pelanggan.profil.store');

    Route::get('/alamat', [AlamatController::class, 'index'])->name('pelanggan.alamat');
    Route::post('/alamat', [AlamatController::class, 'store'])->name('pelanggan.alamat.store');
    Route::put('/alamat/{id}', [AlamatController::class, 'update'])->name('pelanggan.alamat.update');
    Route::delete('/alamat/{id}', [AlamatController::class, 'destroy'])->name('pelanggan.alamat.destroy');

    // Gunakan alias PelangganOrderGudang agar tidak bentrok dengan milik Admin
    Route::controller(PelangganOrderGudang::class)->group(function () {
        Route::get('/titip-barang/create', 'create')->name('pelanggan.titip.create');
        Route::post('/titip-barang', 'store')->name('pelanggan.titip.store');
        Route::get('/titip-barang/{order}', 'show')->name('pelanggan.titip.show');
    });
    Route::get('/booking', [BookingController::class, 'index'])->name('pelanggan.booking.index');

    Route::post('/pembayaran/konfirmasi', [App\Http\Controllers\Pelanggan\BookingController::class, 'konfirmasiPembayaran'])
        ->name('pelanggan.konfirmasi.pembayaran');

    // Cara manual jika tidak pakai ->name('pelanggan.') di group
    Route::get('/jasa-angkutan', [OrderAngkutanController::class, 'index'])->name('pelanggan.order_angkutan.index');
    Route::post('/jasa-angkutan', [OrderAngkutanController::class, 'store'])->name('pelanggan.order_angkutan.store');

    Route::get('/titip-kendaraan', [PelangganOrderKendaraan::class, 'index'])->name('pelanggan.titip-kendaraan.index');
    Route::post('/titip-kendaraan', [PelangganOrderKendaraan::class, 'store'])->name('pelanggan.order-kendaraan.store');

    Route::get('titip-bangunan', [OrderBangunanController::class, 'index'])->name('pelanggan.order-bangunan.index');
    Route::post('titip-bangunan', [OrderBangunanController::class, 'store'])->name('pelanggan.order-bangunan.store');
});


/*
|--------------------------------------------------------------------------
| PROFILE (tetap global, semua user login bisa akses)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze/Fortify)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';



// Letakkan di paling bawah file web.php
Route::post('/tes-barbar', [App\Http\Controllers\Mitra\VerifyController::class, 'store'])->name('mitra.tes.barbar');