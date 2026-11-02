<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlamatPelanggan extends Model
{
    protected $table = 'alamat_pelanggans';

    protected $fillable = [
        'user_id',
        'nama_penerima', // Tambah ini
        'telepon',       // Tambah ini
        'provinsi',      // Pastikan ada
        'kab_kota',
        'kecamatan',
        'alamat_lengkap',
        'catatan_alamat',
        'latitude',
        'longitude', 
        'is_utama'
    ];

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}