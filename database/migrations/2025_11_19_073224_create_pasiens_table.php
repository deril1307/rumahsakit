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
        Schema::create('pasiens', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('no_rm')->unique(); 
                $table->date('tgl_lahir');
                $table->string('jenis_kelamin'); 
                $table->text('alamat')->nullable();
                $table->string('no_telp');
                $table->text('riwayat_medis')->nullable(); 
                $table->string('status')->default('Aktif');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};
