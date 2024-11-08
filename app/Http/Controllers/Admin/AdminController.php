<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;  
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function create()
    {
        return view('admin.administrators.index');  
    }

    public function store(Request $request)
    {
        $request->validate([
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|min:8',
        ]);

        $admin = new User();
        $admin->name = $request->admin_name;
        $admin->email = $request->admin_email;
        $admin->password = Hash::make($request->admin_password);
        $admin->role = 'admin'; // Gán vai trò admin
        $admin->save();

        return redirect()->back()->with('success', 'Admin added successfully!');
    }
    public function show()
{
    $admins = User::where('role', 'admin')->get(); // Lấy danh sách quản trị viên từ database
    return view('admin.administrators.show', compact('admins')); // Trả về view show.blade.php với dữ liệu admins
}

    public function edit($id)
    {
        $admin = User::findOrFail($id); // Tìm admin theo ID
        return view('admin.administrators.edit', compact('admin')); // Trả về view sửa
    }

    public function update(Request $request, $id)
    {
        $admin = User::find($id);
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        
        // Nếu có thay đổi mật khẩu
        if ($request->filled('password')) {
            $admin->password = bcrypt($request->input('password'));
        }

        $admin->save();

        return redirect()->route('admin.administrators.show')->with('success', 'Cập nhật quản trị viên thành công');
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete(); // Xóa admin

        return redirect()->route('admin.administrators.show')->with('success', 'Xóa thành công!');
    }

}
