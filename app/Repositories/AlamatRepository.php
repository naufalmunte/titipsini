<?php

namespace App\Repositories;

use App\Models\AlamatPelanggan;
use Illuminate\Support\Facades\Auth;

class AlamatRepository
{
    public function getAllByUserId($userId)
    {
        return AlamatPelanggan::where('user_id', $userId)->latest()->get();
    }

    public function store(array $data)
    {
        return AlamatPelanggan::create($data);
    }

    public function resetUtama($userId)
    {
        return AlamatPelanggan::where('user_id', $userId)->update(['is_utama' => false]);
    }

    public function countUserAlamat($userId)
    {
        return AlamatPelanggan::where('user_id', $userId)->count();
    }

    public function findById($id) {
    return \App\Models\AlamatPelanggan::findOrFail($id);
}

public function update($id, array $data)
{
    $alamat = \App\Models\AlamatPelanggan::findOrFail($id);
    $alamat->update($data);
    return $alamat;
}
}