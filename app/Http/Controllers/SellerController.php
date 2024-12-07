<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Address;
use App\Models\Button;
use App\Models\Color;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    //
    public function viewShop()
    {
        $selling_right = auth()->user()->selling_right;
        $products = Product::where('seller_id', auth()->id())->get();
        $colors = Color::get();
        $types = Type::get();
        $buttons = Button::get();
        return view('seller_shop', compact('selling_right', 'products', 'colors', 'types', 'buttons'));
    }
    public function request(Request $request)
    {
        
        // Validate incoming request
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        Log::info($user->id . ' request sellign right');        

        // Create or find the address from the form data
        $address = Address::create([
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
        ]);

        // Store the information in the address table
        $user->shop_name = $request->shop_name;
        $user->phone_number = $request->phone_number;
        $user->address_id = $address->id;  // Save the address_id to the user
        $user->selling_right = 'waiting';  // Update selling_right status to 'waiting'

        // Save the user data
        $user->save();

        // Return a response or redirect
        return redirect()->back()->with('success', 'Yêu cầu bán sản phẩm đã được gửi. Chúng tôi sẽ xem xét.');
    }

    public function remove(Product $product)
    {
        $product->delete();
        return redirect()->route('ownShop.view')->with('success', 'Item removed from cart.');
    }


    public function resetSold(Request $request)
    {
        

        // Reset `sold` to 0 for all products of the seller
        Product::where('seller_id', auth()->id())->update(['sold' => 0]);

        // Fetch updated product list for the seller
        $products = Product::where('seller_id', auth()->id())->get();

        // Return success response with the updated products
        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'color' => 'required|exists:colors,id',
            'button' => 'required|exists:button,id',
            'type' => 'required|exists:type,id',
            'representative_img' => 'required|image|mimes:jpg,jpeg,png',
            'detail_imgs' => 'nullable|array',
            'detail_imgs.*' => 'image|mimes:jpg,jpeg,png'
        ]);

        try {
            // Create a new product
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
                'color_id' => $validated['color'],
                'button_id' => $validated['button'],
                'type_id' => $validated['type'],
                'seller_id' => auth()->id(),
                //'material_id'=>$validated['material']
            ]);

            // Save the representative image
            if ($request->hasFile('representative_img')) {
                $representativeImg = $request->file('representative_img');
                
                // Log the file details
                Log::info('File Details:', [
                    'original_name' => $representativeImg->getClientOriginalName(),
                    'mime' => $representativeImg->getMimeType(),
                    'size' => $representativeImg->getSize(),
                ]);

                // Define the path for the representative image
                $representativePath = 'frontend/img/product/' . $product->id . '.jpg';
                
                // Store the image in the public directory (outside the storage directory)
                $representativeImg->move(public_path('frontend/img/product'), $product->id . '.jpg');
                
                // Save the product and image path
                $product->img_path = $representativePath;
                $product->save();
                
            } else {
                Log::warning('No representative image uploaded.');
            }

            // Save detail images (if any)
            if ($request->has('detail_imgs') && count($request->file('detail_imgs')) > 0) {
                $detailImages = $request->file('detail_imgs');
                $productFolder = 'frontend/img/product_description/' . $product->id;

                // Create the directory for product detail images if it doesn't exist
                if (!file_exists(public_path($productFolder))) {
                    mkdir(public_path($productFolder), 0777, true);
                    Log::info('make directory sucesss');

                }
                Log::info('Number of detail images: ' . count($detailImages));

                foreach ($detailImages as $index => $detailImage) {
                    // Since the $index is automatically handled by the foreach loop, no need to reset it
                    $fileName = ($index + 1) . '.jpg'; // img1.jpg, img2.jpg, etc.
                    
                    // Log the file name
                    Log::info('Saving image: ' . $fileName);
                    
                    // Store the image in the appropriate folder
                    $detailImage->move(public_path($productFolder), $fileName);
                    
                    // Save the image path in the database
                    ProductImage::create([
                        'product_id' => $product->id,
                        'img_path' => $productFolder . '/' . $fileName
                    ]);
                    
                    Log::info('Saved detail image to product img');
                }
                
            }

            // Return JSON response with success message and product data
            return response()->json([
                'success'=>'true',
                'message' => 'Product added successfully!',
                'product' => $product,
                'representative_img' => asset('frontend/img/product/' . $product->id . '.jpg'),
                'detail_imgs' => $product->productImages->pluck('img_path')->map(function ($path) {
                    return asset($path);
                })
            ], 201);

        } catch (\Exception $e) {
            // Return a JSON response with error message
            Log::info($e->getMessage());
            return response()->json([
                'message' => 'Failed to add product',
                'error' => $e->getMessage()
            ], 500);
        }
    }




}
