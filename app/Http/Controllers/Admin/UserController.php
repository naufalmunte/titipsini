<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request; 

class UserController extends Controller {
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function index() {
        $users = $this->userService->getUserList();
        return view('admin.user', compact('users'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role'     => 'required|in:admin,mitra,finance,pelanggan',
        ]);

        $this->userService->storeUser($request->all());
        return redirect()->back()->with('success', 'User Baru Berhasil Didaftarkan!');
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'role'     => 'required|in:admin,mitra,finance,pelanggan',
            'password' => 'nullable|min:8',
        ]);

        $this->userService->updateUser($id, $request->all());
        return redirect()->back()->with('success', 'Data User Berhasil Diperbarui!');
    }

    public function destroy($id) {
        $this->userService->deleteUser($id);
        return redirect()->back()->with('success', 'User Telah Dihapus dari Sistem!');
    }
}