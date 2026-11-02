<?php
namespace App\Services;

use App\Repositories\VerifyRepository;
use Illuminate\Support\Facades\Auth;

class VerifyService
{
    protected $verifyRepo;

    public function __construct(VerifyRepository $verifyRepo)
    {
        $this->verifyRepo = $verifyRepo;
    }

    public function submitVerification(array $data)
    {
        // Tambahkan user_id otomatis sebelum simpan
        $data['user_id'] = Auth::id();
        
        return $this->verifyRepo->create($data);
    }

    public function handleVerification(array $data)
    {
        return $this->verifyRepo->updateOrCreate(
            ['user_id' => Auth::id()], // Cari berdasarkan ini
            [
                'nik' => $data['nik'],
                'nama_lengkap' => $data['nama_lengkap'],
                'jenis_kelamin' => $data['jenis_kelamin'] ?? Auth::user()->verify->jenis_kelamin,
                'tgl_lahir' => $data['tgl_lahir'] ?? Auth::user()->verify->tgl_lahir,
                'link_identitas' => $data['link_identitas'],
                'status' => 'pending', // Set balik ke pending biar Admin cek lagi
                'catatan' => null // Hapus alasan penolakan yang lama
            ]
        );
    }
}