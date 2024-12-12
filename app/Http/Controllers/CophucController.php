<?php

namespace App\Http\Controllers;

use App\Models\Cophuc; 
use Illuminate\Http\Request;

class CophucController extends Controller
{
    public function showCophuc()
    {
        $cophuc = Cophuc::all();  
        return view('product.product_type', compact('cophuc'));
    }

    public function show($id)
    {
        // Lấy một sản phẩm theo ID 
        $cophuc = Cophuc::findOrFail($id); 
    
        // Trả về view với thông tin chi tiết
        return view('product.showDetail', compact('cophuc'));
    }
}


