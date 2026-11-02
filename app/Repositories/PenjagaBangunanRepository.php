<?php

namespace App\Repositories;

use App\Models\PenjagaBangunan;

class PenjagaBangunanRepository
{
    public function getAllByMitra($userId)
    {
        return PenjagaBangunan::where('user_id', $userId)->latest()->get();
    }

    public function findById($id)
    {
        return PenjagaBangunan::findOrFail($id);
    }

    public function create(array $data)
    {
        return PenjagaBangunan::create($data);
    }

    public function delete($id)
    {
        $penjaga = $this->findById($id);
        return $penjaga->delete();
    }
}