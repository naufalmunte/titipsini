<?php
namespace App\Repositories;
use App\Models\User;

class UserRepository {
    public function getAllUsers() {
        $query = User::query();

        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        return $query->latest()->paginate(10);
    }

    public function store(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
        ]);
    }

    public function update($id, array $data) {
        $user = User::findOrFail($id);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        
        // Update password cuma kalau diisi
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        
        return $user->save();
    }
        

    public function deleteUser($id) {
        return User::findOrFail($id)->delete();
    }
}