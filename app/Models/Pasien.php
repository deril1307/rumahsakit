<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

/**
 * @property Carbon|null $tgl_lahir  
 */

class Pasien extends Model
{

    use HasFactory;



    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

     */

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



    /**

     * The attributes that should be cast.

     *

     * @var array<string, string>

     */

    protected $casts = [

        'tgl_lahir' => 'date',

    ];



    /**

     * Scope untuk filter status aktif

     */

    public function scopeAktif($query)
    {

        return $query->where('status', 'Aktif');

    }



    /**

     * Scope untuk filter status nonaktif

     */

    public function scopeNonaktif($query)
    {

        return $query->where('status', 'Nonaktif');

    }



    /**

     * Accessor untuk umur (opsional)

     */

    public function getUmurAttribute()
    {
        return $this->tgl_lahir ? $this->tgl_lahir->age : null;
    }

    /**

     * Accessor untuk format tanggal lahir Indonesia (opsional)

     */

    public function getTglLahirFormattedAttribute()
    {
        return $this->tgl_lahir ? $this->tgl_lahir->format('d/m/Y') : '-';
    }
}