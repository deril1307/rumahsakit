<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Kepala\KepalaLaporanController;
// PENTING: Tambahkan ini agar JadwalController dikenali
use App\Http\Controllers\Admin\JadwalController;
// PENTING: Tambahkan ini untuk TerapisDashboardController
use App\Http\Controllers\Terapis\TerapisDashboardController;
// PENTING: Tambahkan ini untuk KepalaDashboardController agar route dashboard kepala berfungsi
use App\Http\Controllers\Kepala\KepalaDashboardController; 

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// PENGALIH OTOMATIS
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// ============================================
// ========== ROUTES ADMIN ====================
// ============================================
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {

    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
         ->name('admin.dashboard');

    // Manajemen Users
    Route::get('/admin/users', [AdminDashboardController::class, 'usersIndex'])
         ->name('admin.users.index');
    Route::get('/admin/users/{user}/edit', [AdminDashboardController::class, 'edit'])
         ->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminDashboardController::class, 'update'])
         ->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminDashboardController::class, 'destroy'])
         ->name('admin.users.destroy');

    // Manajemen Pasien
    Route::get('/admin/pasien', [AdminDashboardController::class, 'pasienIndex'])
         ->name('admin.pasien.index');
    Route::post('/admin/pasien', [AdminDashboardController::class, 'pasienStore'])
         ->name('admin.pasien.store');
    Route::put('/admin/pasien/{id}', [AdminDashboardController::class, 'pasienUpdate'])
         ->name('admin.pasien.update');
    Route::delete('/admin/pasien/{id}', [AdminDashboardController::class, 'pasienDestroy'])
         ->name('admin.pasien.destroy');

    // Manajemen Terapis
    Route::get('/admin/terapis', [AdminDashboardController::class, 'terapisIndex'])
         ->name('admin.terapis.index');
    Route::post('/admin/terapis', [AdminDashboardController::class, 'terapisStore'])
         ->name('admin.terapis.store');
    Route::get('/admin/terapis/{id}/edit', [AdminDashboardController::class, 'terapisEdit'])
         ->name('admin.terapis.edit');
    Route::put('/admin/terapis/{id}', [AdminDashboardController::class, 'terapisUpdate'])
         ->name('admin.terapis.update');
    Route::delete('/admin/terapis/{id}', [AdminDashboardController::class, 'terapisDestroy'])
         ->name('admin.terapis.destroy');

    // === MANAJEMEN JADWAL (UPDATE LENGKAP) ===
    Route::get('/admin/jadwal', [JadwalController::class, 'index'])->name('admin.jadwal.index');
    Route::get('/admin/jadwal/create', [JadwalController::class, 'create'])->name('admin.jadwal.create');
    Route::post('/admin/jadwal', [JadwalController::class, 'store'])->name('admin.jadwal.store');
    
    // Route Edit & Update:
    Route::get('/admin/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('admin.jadwal.edit');
    Route::put('/admin/jadwal/{id}', [JadwalController::class, 'update'])->name('admin.jadwal.update');
    
    // Route Hapus:
    Route::delete('/admin/jadwal/{id}', [JadwalController::class, 'destroy'])->name('admin.jadwal.destroy');

    // Route Cetak PDF (TIKET):
    Route::get('/admin/jadwal/{id}/cetak', [JadwalController::class, 'cetak'])->name('admin.jadwal.cetak');

    // Laporan & Cetak
    Route::get('/admin/laporan', [AdminDashboardController::class, 'laporanIndex'])
         ->name('admin.laporan.index');
    
    // ===> TAMBAHAN BARU: ROUTE EKSPOR PDF ADMIN <===
    Route::get('/admin/laporan/pdf', [AdminDashboardController::class, 'exportPdf'])
         ->name('admin.laporan.pdf');
    
    // Route Cetak Lama
    Route::get('/admin/jadwal/{id_pasien}/cetak-riwayat', [AdminDashboardController::class, 'cetakJadwal'])
         ->name('admin.jadwal.cetak_riwayat');
});

// ============================================
// ========== ROUTES TERAPIS ==================
// ============================================
Route::middleware(['auth', 'verified', 'role:terapis'])->group(function () {
    
    // Dashboard Terapis
    Route::get('/terapis/dashboard', [TerapisDashboardController::class, 'index'])
         ->name('terapis.dashboard');

    // Route untuk update status via Tombol Cepat
    Route::patch('/terapis/jadwal/{id}/status', [TerapisDashboardController::class, 'updateStatus'])
         ->name('terapis.jadwal.updateStatus');

    // Route Edit Jadwal Terapis
    Route::get('/terapis/jadwal/{id}/edit', [TerapisDashboardController::class, 'edit'])
         ->name('terapis.jadwal.edit');
         
    // Route Proses Update Terapis
    Route::put('/terapis/jadwal/{id}', [TerapisDashboardController::class, 'updateStatus'])
         ->name('terapis.jadwal.update');

});

// ============================================
// ========== ROUTES KEPALA ===================
// ============================================
Route::middleware(['auth', 'verified', 'role:kepala'])->group(function () {
    
    // Dashboard Kepala
    Route::get('/kepala/dashboard', [KepalaDashboardController::class, 'index'])
        ->name('kepala.dashboard');

    // Laporan Kepala (Method Index)
    Route::get('/kepala/laporan', [KepalaLaporanController::class, 'index'])
         ->name('kepala.laporan');
         
    // Cetak Laporan Kepala (Method Baru)
    Route::get('/kepala/laporan/cetak', [KepalaLaporanController::class, 'cetakPdf'])
         ->name('kepala.laporan.cetak');
});

// ============================================
// ========== ROUTES PROFILE ==================
// ============================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';