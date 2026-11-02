<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAngkutan extends Model
{
    use HasFactory;

    // Tentukan kolom yang boleh diisi
   protected $fillable = [
    'jenis_armada_id',
    'alamat_jemput_id',
    'alamat_tujuan_id',
    'jarak_km',
    'tanggal_angkut',
    'jam_angkut',
    'catatan_muatan',
    'status',
];

    /**
     * Relasi Balik ke Tabel Orders (Pusat)
     * Menggunakan Polymorphic: layanan_type & layanan_id
     */
public function order()
{
    // Karena di tabel orders kolomnya adalah layanan_id dan layanan_type
    return $this->morphOne(Order::class, 'layanan');
}

    /**
     * Relasi ke Jenis Armada
     */
    public function jenisArmada()
    {
        return $this->belongsTo(JenisArmada::class, 'jenis_armada_id');
    }

    /**
     * Relasi ke Alamat Penjemputan
     */
    public function alamatJemput()
    {
        return $this->belongsTo(AlamatPelanggan::class, 'alamat_jemput_id');
    }

    /**
     * Relasi ke Alamat Tujuan
     */
    public function alamatTujuan()
    {
        return $this->belongsTo(AlamatPelanggan::class, 'alamat_tujuan_id');
    }
    
}