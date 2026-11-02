<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomisiSetting extends Model
{
    use HasFactory;

    protected $fillable = ['persen_mitra'];
}