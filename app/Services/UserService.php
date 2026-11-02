<?php
namespace App\Services;
use App\Repositories\UserRepository;

class UserService {
    protected $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function getUserList() {
        return $this->userRepo->getAllUsers();
    }

    public function storeUser(array $data) {
        return $this->userRepo->store($data);
    }

    public function updateUser($id, array $data) {
        return $this->userRepo->update($id, $data);
    }

    public function deleteUser($id) {
        return $this->userRepo->deleteUser($id);
    }
}