<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JenisTerapiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. HAPUS DATA YANG TIDAK DIPAKAI / TYPO
        // Kita gunakan whereIn untuk menghapus banyak sekaligus ('Psikoligi' typo & 'Psikologi' asli)
        DB::table('jenis_terapis')
            ->whereIn('nama_terapi', ['Psikoligi', 'Psikologi'])
            ->delete();

        // 2. Daftar Lengkap Jenis Terapi (Hanya yang aktif)
        // Catatan: 'Psikologi' SUDAH DIHAPUS dari daftar ini
        $dataTerapi = [
            'Fisioterapi',
            'Terapi Okupasi',
            'Terapi Wicara',
            // === TAMBAHAN BARU ===
            'Fisioterapi Anak',
            'Fisioterapi Stroke',
        ];

        // 3. Loop untuk Insert atau Update (Agar aman dijalankan berkali-kali)
        foreach ($dataTerapi as $nama) {
            DB::table('jenis_terapis')->updateOrInsert(
                // Kondisi Pengecekan (Cari berdasarkan nama)
                ['nama_terapi' => $nama], 
                
                // Data yang akan disimpan/diupdate
                [
                    'created_at' => $now, 
                    'updated_at' => $now
                ]
            );
        }
    }
}