<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller

{
    public function getProducts(): JsonResponse
    {
        $products = Product::all();

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with(['image', 'type'])->findOrFail($id);

        return view('product.product_description', compact('product'));
    }

    public function index()
    {
        $products = Product::with('type')->get(); 
        $type = Type::all();

        return view('admin.products.index', compact('products', 'type'));  
    }
 
    public function productList()
{
        $products = Product::with('type')->get(); 
        $type = Type::all(); 
        return view('product.product_list', compact('products', 'type'));  
}

    public function create()
    {
        $type = Type::all(); 
        return view('admin.products.create', compact('type'));
    }

        public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'description' => 'nullable',
        'price' => 'required|numeric',
        'type_id' => 'required|exists:type,id',  
        'img' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', 
    ]);

    $product = new Product();
    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;

    if ($request->hasFile('img')) {
        $imagePath = $request->file('img')->store('products', 'public'); // Lưu vào thư mục public/products
        $product->img_path = $imagePath;  
    }

    // Lưu loại sản phẩm
    $product->type_id = $request->type_id; // Gán loại sản phẩm vào product

    $product->save();
    
    return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm thành công.');
}

public function edit($id)
{
    $product = Product::findOrFail($id);
    $types = Type::all(); 

    return view('admin.products.edit', compact('product', 'types'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'description' => 'nullable',
        'price' => 'required|numeric',
        'type_id' => 'required|exists:type,id',
        'img' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $product = Product::findOrFail($id);
    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->type_id = $request->type_id;

    if ($request->hasFile('img')) {
        $imagePath = $request->file('img')->store('products', 'public');
        $product->img_path = $imagePath;
    }

    $product->save();

    return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
}

}
