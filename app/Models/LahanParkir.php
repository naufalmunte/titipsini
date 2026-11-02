<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LahanParkir extends Model
{
    use HasFactory;

    // Supaya semua kolom bisa diisi massal kecuali ID
    protected $guarded = ['id'];

// app/Models/LahanParkir.php
    public function hitungSisaKapasitas($kategoriId) {
        $mappingKapasitas = [1 => 'kapasitas_roda_2', 2 => 'kapasitas_roda_3', 3 => 'kapasitas_roda_4'];
        $kolomKapasitas = $mappingKapasitas[$kategoriId];

        // Hitung unit yang statusnya: menunggu_kedatangan ATAU dititip ATAU masa_titip_habis
        $terisi = $this->orderKendaraans()
            ->whereIn('status', ['menunggu_kedatangan', 'dititip', 'masa_titip_habis'])
            ->whereHas('items', function($q) use ($kategoriId) {
                $q->where('kategori_id', $kategoriId);
            })->count();

        return $this->$kolomKapasitas - $terisi;
    }

    /**
     * Relasi: Satu Lahan Parkir dimiliki oleh satu Mitra (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderKendaraans()
    {
        // Pastikan foreign key 'lahan_id' ada di tabel order_kendaraans
        return $this->hasMany(OrderKendaraan::class, 'lahan_id');
    }
}