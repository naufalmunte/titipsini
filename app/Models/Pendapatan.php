<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    use HasFactory;

    // JEDER! Masukkan kolom-kolom ini agar bisa diisi massal
   protected $fillable = [
    'order_id', 
    'mitra_id', 
    'kategori_layanan', 
    'nominal_mitra', 
    'nominal_titipsini', 
    'status_payout',
    'persen_mitra', // JEDER! Pastikan baris ini ADA
];
    // Jangan lupa relasinya supaya tabel di Blade tidak error
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }
}