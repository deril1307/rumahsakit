<?php

namespace Database\Seeders;

// (use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Ini boleh ada atau dihapus)
use Illuminate\Database\Seeder;

// 1. Tambahkan impor untuk Role dan Permission
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2. Isi method run() dengan kode ini:

        // Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ---- BUAT PERMISSIONS (IZIN) ----
        // Ini adalah izin-izin sesuai dokumen SRS Anda
        Permission::create(['name' => 'buat-jadwal']);
        Permission::create(['name' => 'update-status-pasien']);
        Permission::create(['name' => 'lihat-laporan']);
        Permission::create(['name' => 'kelola-user']); 




        // ---- BUAT ROLES ----
        $roleAdmin = Role::create(['name' => 'admin']);       // Role untuk Petugas Administratif
        $roleTerapis = Role::create(['name' => 'terapis']);   // Role untuk Terapis
        $roleKepala = Role::create(['name' => 'kepala']);     // Role untuk Kepala Instalasi

        // ---- BERI PERMISSION KE ROLES ----
        
        // Admin (Petugas Administratif) bisa semuanya
        $roleAdmin->givePermissionTo([
            'buat-jadwal',
            'update-status-pasien',
            'lihat-laporan',
            'kelola-user'
        ]);

        // Terapis hanya bisa lihat jadwal & update status
        $roleTerapis->givePermissionTo([
            'update-status-pasien'
        ]);

        // Kepala hanya bisa lihat laporan
        $roleKepala->givePermissionTo([
            'lihat-laporan'
        ]);
    }
}