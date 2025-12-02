<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel pasiens
            $table->foreignId('pasien_id')
                  ->constrained('pasiens')
                  ->onDelete('cascade'); // Jika pasien dihapus, jadwalnya juga terhapus
            
            // Relasi ke tabel users (terapis)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // Jika terapis dihapus, jadwalnya juga terhapus
            
            // Data terapi
            $table->string('jenis_terapi'); // Fisioterapi, Terapi Okupasi, dll
            $table->date('tanggal'); // Tanggal terapi
            $table->time('jam_mulai'); // Jam mulai terapi
            $table->time('jam_selesai'); // Jam selesai terapi
            $table->string('ruangan')->nullable(); // Ruangan terapi (opsional)
            
            // Status terapi
            $table->enum('status', [
                'terjadwal',  // Jadwal sudah dibuat, belum dilaksanakan
                'selesai',    // Terapi sudah selesai
                'batal',      // Terapi dibatalkan
                'pending'     // Menunggu konfirmasi
            ])->default('terjadwal');
            
            // Catatan dari terapis setelah terapi
            $table->text('catatan')->nullable();
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('tanggal');
            $table->index('status');
            $table->index(['user_id', 'tanggal']); // Cek jadwal terapis per tanggal
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};