<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamOperasional extends Model
{
    protected $table = 'jam_operasional';
    protected $fillable = ['user_id', 'hari', 'jam_buka', 'jam_tutup', 'is_buka'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}