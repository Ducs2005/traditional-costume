<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Type;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class ProductController extends Controller
{
    public function getProducts(): JsonResponse
    {
        // Fetch all products from the database
        $products = Product::all();

        // Return products as a JSON response
        return response()->json($products);
    }
    public function show($id)
    {
        // Eager load the product, its images, and types
        $product = Product::with(['images', 'types'])->findOrFail($id);

        // Pass the product (with images and types) to the view
        return view('product/product_description', compact('product'));
    }
                
}
