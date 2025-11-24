<?php



use App\Http\Controllers\ProfileController;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Admin\AdminDashboardController;

use App\Http\Controllers\Kepala\KepalaLaporanController;

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

   

    // Laporan & Cetak

    Route::get('/admin/laporan', [AdminDashboardController::class, 'laporanIndex'])

         ->name('admin.laporan.index');

    Route::get('/admin/jadwal/{id_pasien}/cetak', [AdminDashboardController::class, 'cetakJadwal'])

         ->name('admin.jadwal.cetak');

});



// ============================================

// ========== ROUTES TERAPIS ==================

// ============================================

Route::middleware(['auth', 'verified', 'role:terapis'])->group(function () {

    Route::get('/terapis/dashboard', function () {

        return view('terapis.dashboard');

    })->name('terapis.dashboard');

});



// ============================================

// ========== ROUTES KEPALA ===================

// ============================================

Route::middleware(['auth', 'verified', 'role:kepala'])->group(function () {

    Route::get('/kepala/dashboard', function () {

        return view('kepala.dashboard');

    })->name('kepala.dashboard');



    Route::get('/kepala/laporan', [KepalaLaporanController::class, 'index'])

         ->name('kepala.laporan');

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
