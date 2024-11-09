<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Type;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class ProductController extends Controller
{
    public function getProducts(): JsonResponse
    {
        // Fetch all products with their related attributes
        $products = Product::with(['color', 'material', 'button', 'types'])->get();

        // Return products as a JSON response
        return response()->json($products);
    }
    

    public function show($id)
    {
        // Eager load the product with its related attributes including the seller (User)
        $product = Product::with(['color', 'material', 'button', 'types', 'seller'])->findOrFail($id);

        // Pass the product to the view
        return view('product.product_description', compact('product'));
    }



    public function filterProducts(Request $request)
    {
        $query = Product::query();

        // Apply filters
        if ($request->filled('color_id')) {
            $query->where('color_id', $request->color_id);
        }
        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }
        if ($request->filled('button_id')) {
            $query->where('button_id', $request->button_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Apply sorting
        if ($request->sort_order === 'byName') {
            $query->orderBy('name');
        } elseif ($request->sort_order === 'byPriceDecrease') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort_order === 'byPriceIncrease') {
            $query->orderBy('price', 'asc');
        }

        // Get the products
        $products = $query->with(['color', 'material', 'button'])->get();

        return response()->json(['products' => $products]);
    }

   

                
}
