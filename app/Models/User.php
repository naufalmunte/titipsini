<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function verify()
    {
        return $this->hasOne(Verify::class);
    }

    public function alamats()
    {
        return $this->hasMany(AlamatPelanggan::class, 'user_id');
    }

    public function profilMitra()
    {
        return $this->hasOne(ProfilMitra::class);
    }

    public function jamOperasional()
    {
        return $this->hasMany(JamOperasional::class);
    }

    public function gudangPenitipans()
    {
        return $this->hasMany(GudangPenitipan::class);
    }

    public function jenisArmada()
    {
        return $this->belongsTo(JenisArmada::class, 'jenis_armada_id');
    }

    public function armada()
    {
        return $this->hasMany(ArmadaMitra::class, 'user_id');
    }
    
    public function orders()
    {
        // Menggunakan mitra_id sebagai foreign key di tabel orders
        return $this->hasMany(Order::class, 'mitra_id');
    }

    
        
}
