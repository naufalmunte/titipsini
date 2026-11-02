<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    // JEDER! Tambahkan mitra_id di sini agar diizinkan masuk ke database
    protected $fillable = [
    'mitra_id',
    'rekening_id',
    'nominal',
    'status',
    'bukti_transfer', // Tambahkan ini
    'catatan_admin',  // Tambahkan ini (catatan_admin sesuai DB kamu)
];

    // Relasi ke User/Mitra (Optional tapi disarankan)
    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    // Relasi ke Rekening
    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }
}