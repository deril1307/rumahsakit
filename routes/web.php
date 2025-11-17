<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Kepala\KepalaLaporanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// INI AKAN JADI PENGALIH OTOMATIS
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// --- HALAMAN KHUSUS ADMIN ---
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
         ->name('admin.dashboard');
    Route::get('/admin/users', [AdminDashboardController::class, 'usersIndex'])
         ->name('admin.users.index');
    // ... rute edit, update, destroy ...
    Route::get('/admin/users/{user}/edit', [AdminDashboardController::class, 'edit'])
         ->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminDashboardController::class, 'update'])
         ->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminDashboardController::class, 'destroy'])
         ->name('admin.users.destroy');
    Route::get('/admin/jadwal/{id_pasien}/cetak', [AdminDashboardController::class, 'cetakJadwal'])
         ->name('admin.jadwal.cetak');

    Route::get('/admin/laporan', [AdminDashboardController::class, 'laporanIndex'])
         ->name('admin.laporan.index');

    Route::get('/admin/pasien', [AdminDashboardController::class, 'pasienIndex'])->name('admin.pasien.index');

   
     Route::get('/admin/terapis', [AdminDashboardController::class, 'terapisIndex'])->name('admin.terapis.index');
     Route::post('/admin/terapis', [AdminDashboardController::class, 'terapisStore'])->name('admin.terapis.store');
});

// --- HALAMAN KHUSUS TERAPIS ---
Route::middleware(['auth', 'verified', 'role:terapis'])->group(function () {
    Route::get('/terapis/dashboard', function () {
        return view('terapis.dashboard'); 
    })->name('terapis.dashboard');
});

// --- HALAMAN KHUSUS KEPALA ---
Route::middleware(['auth', 'verified', 'role:kepala'])->group(function () {
   // RUTE 1: Untuk Dashboard (kosong) Anda
    Route::get('/kepala/dashboard', function () {
        return view('kepala.dashboard'); // Ini ke file blade 'kepala/dashboard.blade.php'
    })->name('kepala.dashboard'); // Beri nama 'kepala.dashboard'

    // RUTE 2: Untuk Laporan Kepala Instalasi
    Route::get('/kepala/laporan', [KepalaLaporanController::class, 'index'])
         ->name('kepala.laporan'); // Ini ke controller Laporan
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
