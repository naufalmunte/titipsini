<?php

namespace App\Repositories;

use App\Models\LahanParkir;

class LahanParkirRepository
{
    public function getByUserId($userId)
    {
        return LahanParkir::where('user_id', $userId)->latest()->paginate(10);
    }

    public function getAllWithUser()
    {
        // Khusus untuk halaman Admin nanti
        return LahanParkir::with('user')->latest()->paginate(10);
    }

    public function create(array $data)
    {
        return LahanParkir::create($data);
    }

    public function findById($id)
    {
        return LahanParkir::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $parkir = $this->findById($id);
        $parkir->update($data);
        return $parkir;
    }

    public function delete($id)
    {
        $parkir = $this->findById($id);
        return $parkir->delete();
    }
}