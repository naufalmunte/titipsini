<?php

namespace App\Repositories;

use App\Models\GudangPenitipan;

class GudangRepository
{
    public function getByUserId($userId)
    {
        return GudangPenitipan::where('user_id', $userId)->latest()->paginate(10);
    }

    public function create(array $data)
    {
        return GudangPenitipan::create($data);
    }

    public function findById($id)
    {
        return GudangPenitipan::findOrFail($id);
    }

    // FUNGSI BARU UNTUK EDIT
    public function update($id, array $data)
    {
        $gudang = $this->findById($id);
        $gudang->update($data);
        return $gudang;
    }

    // FUNGSI BARU UNTUK HAPUS
    public function delete($id)
    {
        $gudang = $this->findById($id);
        return $gudang->delete();
    }

    // Tambahkan fungsi ini untuk Admin
    public function getAllWithUser()
    {
        return GudangPenitipan::with('user')->latest()->paginate(10);
    }
}