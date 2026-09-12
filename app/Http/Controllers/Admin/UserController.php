<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        // Chỉ lấy danh sách tài khoản là customer
        $users = User::where('role', 'customer')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        if ($user->role !== 'admin') {
            $user->delete();
        }
        return redirect()->back()->with('success', 'Xóa tài khoản khách hàng thành công!');
    }
}