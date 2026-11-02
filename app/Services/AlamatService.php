<?php

namespace App\Services;

use App\Repositories\AlamatRepository;
use Illuminate\Support\Facades\Auth;

class AlamatService
{
    protected $alamatRepo;

    public function __construct(AlamatRepository $alamatRepo)
    {
        $this->alamatRepo = $alamatRepo;
    }

    public function getAlamatUser()
    {
        return $this->alamatRepo->getAllByUserId(Auth::id());
    }

    public function simpanAlamat(array $data)
{
    $userId = Auth::id();
    
    // JEDER! Cek limit 5 alamat
    if ($this->alamatRepo->countUserAlamat($userId) >= 5) {
        throw new \Exception("Maksimal hanya boleh simpan 5 alamat, Raja! Hapus yang lama dulu ya.");
    }

    $data['user_id'] = $userId;

    if (isset($data['is_utama']) && $data['is_utama'] == true) {
        $this->alamatRepo->resetUtama($userId);
    }

    if ($this->alamatRepo->countUserAlamat($userId) == 0) {
        $data['is_utama'] = true;
    }

    return $this->alamatRepo->store($data);
}


public function perbaruiAlamat($id, array $data)
{
    $userId = auth()->id();

    // Jika user mencentang 'is_utama', matikan is_utama di alamat lain milik user ini
    if (isset($data['is_utama']) && $data['is_utama'] == true) {
        $this->alamatRepo->resetUtama($userId);
    }

    return $this->alamatRepo->update($id, $data);
}

// Tambahkan fungsi hapus
public function hapusAlamat($id)
{
    $alamat = $this->alamatRepo->findById($id);
    if ($alamat->user_id !== Auth::id()) abort(403);
    
    // Jika yang dihapus alamat utama, pindahkan status utama ke alamat lain yang tersisa
    if ($alamat->is_utama) {
        $alamat->delete();
        $sisaAlamat = $this->alamatRepo->getAllByUserId(Auth::id())->first();
        if ($sisaAlamat) $sisaAlamat->update(['is_utama' => true]);
    } else {
        $alamat->delete();
    }
}
}