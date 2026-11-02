<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilMitra extends Model
{
    // Nama tabel di database
    protected $table = 'profil_mitra';

    /**
     * Properti fillable harus mencakup semua kolom baru agar bisa 
     * disimpan menggunakan metode ProfilMitra::create() atau $model->update().
     */
    // app/Models/ProfilMitra.php
protected $fillable = [
    'user_id', 
    'nama_pt', 
    'provinsi', 
    'kab_kota', 
    'kecamatan', 
    'alamat', 
    'latitude', 
    'longitude'
];

    /**
     * Relasi ke model User (Pemilik profil)
     */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}