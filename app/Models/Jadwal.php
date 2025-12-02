<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Jadwal extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'jadwals';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'pasien_id',
        'user_id',
        'jenis_terapi',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'status',
        'catatan',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // ============================================
    // ========== RELASI (RELATIONSHIPS) ==========
    // ============================================

    /**
     * Relasi ke Pasien
     * Setiap jadwal punya 1 pasien
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    /**
     * Relasi ke Terapis (User)
     * Setiap jadwal punya 1 terapis
     */
    public function terapis()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ============================================
    // ========== SCOPE (QUERY FILTER) ============
    // ============================================

    /**
     * Scope untuk filter jadwal berdasarkan status
     * Cara pakai: Jadwal::status('selesai')->get();
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter jadwal hari ini
     * Cara pakai: Jadwal::hariIni()->get();
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', Carbon::today());
    }

    /**
     * Scope untuk filter jadwal minggu ini
     * Cara pakai: Jadwal::mingguIni()->get();
     */
    public function scopeMingguIni($query)
    {
        return $query->whereBetween('tanggal', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    /**
     * Scope untuk filter jadwal berdasarkan terapis
     * Cara pakai: Jadwal::byTerapis(1)->get();
     */
    public function scopeByTerapis($query, $terapisId)
    {
        return $query->where('user_id', $terapisId);
    }

    /**
     * Scope untuk filter jadwal berdasarkan pasien
     * Cara pakai: Jadwal::byPasien(1)->get();
     */
    public function scopeByPasien($query, $pasienId)
    {
        return $query->where('pasien_id', $pasienId);
    }

    // ============================================
    // ========== ACCESSOR (GET ATTRIBUTE) ========
    // ============================================

    /**
     * Accessor untuk format tanggal Indonesia
     * Cara pakai: $jadwal->tanggal_formatted
     */
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    /**
     * Accessor untuk format tanggal lengkap Indonesia
     * Cara pakai: $jadwal->tanggal_lengkap
     */
    public function getTanggalLengkapAttribute()
    {
        if (!$this->tanggal) return '-';
        
        $hari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        
        return $hari[$this->tanggal->format('l')] . ', ' . $this->tanggal->format('d M Y');
    }

    /**
     * Accessor untuk badge status (warna)
     * Cara pakai: $jadwal->status_badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'terjadwal' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Terjadwal</span>',
            'selesai' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>',
            'batal' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Batal</span>',
            'pending' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Pending</span>',
        ];

        return $badges[$this->status] ?? $this->status;
    }

    // ============================================
    // ========== HELPER METHODS ==================
    // ============================================

    /**
     * Cek apakah jadwal sudah selesai
     */
    public function isSelesai()
    {
        return $this->status === 'selesai';
    }

    /**
     * Cek apakah jadwal dibatalkan
     */
    public function isBatal()
    {
        return $this->status === 'batal';
    }

    /**
     * Cek apakah jadwal masih terjadwal
     */
    public function isTerjadwal()
    {
        return $this->status === 'terjadwal';
    }
}