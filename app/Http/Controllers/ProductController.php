<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Color;
use App\Models\Button;
use App\Models\Material;

use App\Models\Type;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class ProductController extends Controller
{
    public function getProducts(): JsonResponse
    {
        // Fetch all products with their related attributes
        $products = Product::with(['color', 'material', 'button', 'type', 'ratings'])
            ->where('seller_id', '!=', auth()->id())
            ->get()
            ->map(function ($product) {
                // Calculate the average rating
                $averageRating = $product->ratings->isNotEmpty()
                    ? $product->ratings->avg('rating')
                    : 5;

                // Get the number of ratings
                $numberOfRatings = $product->ratings->count();

                // Add the average rating and number of ratings to the product attributes
                $product->average_rating = $averageRating;
                $product->number_of_ratings = $numberOfRatings;

                return $product;
            });

        // Return products as a JSON response
        return response()->json($products);
    }

    public function show($id)
    {
        // Eager load the product with its related attributes including the seller (User)
        $product = Product::with(['color', 'productImages', 'material', 'button', 'type', 'seller', 'ratings.user'])->findOrFail($id);
    
        // Calculate the average rating
        $averageRating = $product->ratings->isNotEmpty()
            ? $product->ratings->avg('rating')
            : 5;
    
        // Get the number of ratings
        $numberOfRatings = $product->ratings->count();
    
        // Add the average rating and number of ratings as attributes
        $product->average_rating = $averageRating;
        $product->number_of_ratings = $numberOfRatings;
    
        // Pass the product to the view
        return view('product.product_description', compact('product'));
    }
    



    public function filterProducts(Request $request)
    {
        $query = Product::query();
        $query->with(['color', 'material', 'button', 'type']); // Add the necessary relationships here

        // Apply filters
        if ($request->filled('color_id')) {
            $query->where('color_id', $request->color_id);
        }
        if ($request->filled('type_id')) {
            $query->where('type_id', $request->type_id);
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
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        if ($request->filled('rating_filter')) {
            $query->whereHas('ratings', function ($q) use ($request) {
                $q->havingRaw('AVG(rating) >= ?', [$request->rating_filter]);
            });
        }

        // Apply sorting
        if ($request->sort_order === 'byName') {
            $query->orderBy('name');
        } elseif ($request->sort_order === 'byRatingIncrease') {
            $query->withAvg('ratings', 'rating')->orderBy('ratings_avg_rating', 'asc');
        } elseif ($request->sort_order === 'byRatingDecrease') {
            $query->withAvg('ratings', 'rating')->orderBy('ratings_avg_rating', 'desc');
        } elseif ($request->sort_order === 'byPriceIncrease') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort_order === 'byPriceDecrease') {
            $query->orderBy('price', 'desc');
        }
    


        // Get the products
        $products = $query->get()->map(function ($product) {
            // Calculate the average rating
            $averageRating = $product->ratings->isNotEmpty()
                ? $product->ratings->avg('rating')
                : 5;
    
            // Get the number of ratings
            $numberOfRatings = $product->ratings->count();
    
            // Add the average rating and number of ratings to the product attributes
            $product->average_rating = $averageRating;
            $product->number_of_ratings = $numberOfRatings;
    
            return $product;
        });
    

        return response()->json(['products' => $products]);
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
            $types = Type::all();
            $colors = Color::all();
            $materials = Material::all();
            $buttons = Button::all();

            $discountedProducts = Product::whereBetween('price', [100000, 300000])->get();
            $popularProducts = Product::where('sold', '>=', 100)->get();
            return view('product.product_list', compact('products', 'types', 'colors', 'materials', 'buttons', 'discountedProducts', 'popularProducts'));
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

    public function update($attribute, $id)
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

    public function showCategory($attribute, $id)
    {
        $attributeData = null;

        // Switch case to fetch attribute data based on the type
        switch ($attribute) {
            case 'color':
                $attributeData = Color::findOrFail($id);
                break;

            case 'material':
                $attributeData = Material::findOrFail($id);
                break;

            case 'button':
                $attributeData = Button::findOrFail($id);
                break;

            case 'type':
                $attributeData = Type::findOrFail($id);
                break;

            default:
                abort(404, 'Invalid attribute type');
        }

        // Fetch all products to show in the view (optional)
        $products = Product::with('type')->get();

        // Retrieve other data
        $types = Type::all();
        $colors = Color::all();
        $materials = Material::all();
        $buttons = Button::all();
        $discountedProducts = Product::whereBetween('price', [100000, 300000])->get();
        $popularProducts = Product::where('sold', '>=', 100)->get();

        // Pass data to the view
        return view('product.product_list', compact('products', 'colors', 'materials', 'buttons', 'types', 'attribute', 'id', 'attributeData', 'discountedProducts', 'popularProducts'));
    }

}

