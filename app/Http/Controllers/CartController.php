<?php

namespace App\Http\Controllers;
use App\Models\Cart;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity', 1); // Retrieve quantity from request, default to 1 if not provided

            // Assuming user or session cart logic
            $cart = Cart::firstOrCreate([
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
            ]);

            // Check if the product is already in the cart
            $cartItem = $cart->items()->where('product_id', $productId)->first();
            
            if ($cartItem) {
                $cartItem->quantity += $quantity; // Update quantity
                $cartItem->save();
            } else {
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }

            return redirect()->back()->with('success', 'Product added to cart successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add product to cart.');
        }
    }

}
