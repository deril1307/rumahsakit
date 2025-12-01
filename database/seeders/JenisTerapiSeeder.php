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

        $data = [
            ['nama_terapi' => 'Fisioterapi', 'created_at' => $now, 'updated_at' => $now],
            ['nama_terapi' => 'Terapi Okupasi', 'created_at' => $now, 'updated_at' => $now],
            ['nama_terapi' => 'Terapi Wicara', 'created_at' => $now, 'updated_at' => $now],
            ['nama_terapi' => 'Psikologi', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('jenis_terapis')->insert($data);
    }
}