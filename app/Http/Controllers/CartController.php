<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class CartController extends Controller
{

    public function viewCart()
    {
        // Fetch the user's cart
        $cart = Cart::where('user_id', auth()->id())->first(); // Assuming the user is logged in

        // If cart exists, get the cart items along with product details
        if ($cart) {
            $cartItems = $cart->items()->with('product')->get(); // This will retrieve the cart items with product details
        } else {    
            $cartItems = []; // No cart found
        }
        // Pass the cart items to the view
        return view('view_cart', compact('cartItems'));
    }
    
    public function removeItem(CartItem $cartItem)
    {
        $cartItem->delete();  // Deletes the cart item

        // Redirect or return a response with updated cart data
        return redirect()->route('cart.view')->with('success', 'Item removed from cart.');
    }
    public function clearCart(Request $request)
    {
        // Clear cart items for the current user or session
        Cart::where('user_id', auth()->id())->delete();

        // Return a JSON response
        return response()->json(['success' => true]);
    }


    public function addToCart(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $quantity = $request->input('quantity', 1); // Retrieve quantity from request, default to 1 if not provided

            // Find or create the user's cart. A user can only have one cart.
            $cart = Cart::firstOrCreate([
                'user_id' => auth()->id(),  // Find cart by user ID, this ensures only one cart per user
                'session_id' => null,  // If the user is logged in, session_id should be null
            ]);

            // Check if the product is already in the cart
            $cartItem = $cart->items()->where('product_id', $productId)->first();

            if ($cartItem) {
                // If the item is already in the cart, update the quantity
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                // If the item is not in the cart, create a new cart item
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }

            return redirect()->back()->with('success', 'Product added to cart successfully.');
        } catch (\Exception $e) {
            Log::info($e);
            return redirect()->back()->with('error', 'Failed to add product to cart.');
        }
    }

    public static function getCartItemCount()
    {
        $cart = Cart::with('items')
                    ->where('user_id', auth()->id())
                    ->orWhere('session_id', session()->getId())
                    ->first();

        Log::info($cart ? $cart : 'No items found');

        return $cart ? $cart->items->sum('quantity') : 0;
    }



}
