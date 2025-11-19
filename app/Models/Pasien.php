<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'no_rm',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telp',
        'riwayat_medis',
        'status',
    ];
}
