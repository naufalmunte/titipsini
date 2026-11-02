<?php
namespace App\Repositories;

use App\Models\Verify;

class VerifyRepository
{
    public function create(array $data)
    {
        return Verify::create($data);
    }

    public function findByUserId($userId)
    {
        return Verify::where('user_id', $userId)->first();
    }

    public function updateOrCreate(array $attributes, array $values)
    {
        return Verify::updateOrCreate($attributes, $values);
    }
}