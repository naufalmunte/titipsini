<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBangunan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'harga_per_hari',
        'deskripsi',
    ];
}
