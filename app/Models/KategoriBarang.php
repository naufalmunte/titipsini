<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    use HasFactory;

    // Tambahkan ini supaya bisa input data lewat Seeder/Controller
    protected $guarded = ['id']; 
}