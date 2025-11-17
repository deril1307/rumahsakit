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
        Schema::table('users', function (Blueprint $table) {
        // Kolom khusus Terapis (Nullable karena Admin/Kepala tidak butuh ini)
        $table->string('spesialisasi')->nullable()->after('email');
        $table->string('no_telp')->nullable()->after('spesialisasi');
        // Status user (Aktif/Nonaktif), default-nya Aktif
        $table->string('status')->default('Aktif')->after('no_telp');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
