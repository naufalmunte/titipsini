<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verify extends Model
{
    // Nama tabel kita di database
    protected $table = 'verifies';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'user_id', 
        'nik', 
        'nama_lengkap', 
        'jenis_kelamin', 
        'tgl_lahir', 
        'link_identitas', 
        'pin',
        'status',
        'catatan'
    ];

    /**
     * Hubungan sebaliknya: Data verifikasi ini milik satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}