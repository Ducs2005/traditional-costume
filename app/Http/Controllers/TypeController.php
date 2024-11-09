<?php

namespace App\Http\Controllers;

use App\Models\Type;  // Model Type
use Illuminate\Http\Request;

class TypeController extends Controller
{
    // Hiển thị danh sách loại sản phẩm
    public function index()
    {
        $types = Type::all();  // Lấy tất cả các loại sản phẩm
        return view('admin.types.index', compact('types'));  // Trả về view với dữ liệu loại sản phẩm
    }

    // Hiển thị form tạo mới loại sản phẩm
    public function create()
    {
        return view('admin.types.create');
    }

    // Xử lý lưu loại sản phẩm mới
    public function store(Request $request)
{
    // Kiểm tra dữ liệu đầu vào
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',   
    ]);

    // Tạo loại sản phẩm mới
    Type::create([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? 'Default description', // Giá trị mặc định nếu không có input
    ]);

    return redirect()->route('admin.types.index')->with('success', 'Thêm loại sản phẩm thành công');
}

    // Hiển thị form chỉnh sửa loại sản phẩm
    public function edit($id)
    {
        $type = Type::findOrFail($id);  // Tìm loại sản phẩm theo ID
        return view('admin.types.edit', compact('type'));
    }

    // Xử lý cập nhật loại sản phẩm
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $type = Type::findOrFail($id);  // Tìm loại sản phẩm theo ID
        $type->update([
            'name' => $validated['name'],
        ]);

        return redirect()->route('admin.types.index')->with('success', 'Cập nhật loại sản phẩm thành công');
    }

    // Xử lý xóa loại sản phẩm
    public function destroy($id)
    {
        $type = Type::findOrFail($id);  // Tìm loại sản phẩm theo ID
        $type->delete();  // Xóa loại sản phẩm

        return redirect()->route('admin.types.index')->with('success', 'Xóa loại sản phẩm thành công');
    }
}
