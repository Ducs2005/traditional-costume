<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use DB;
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
        // Get the cart for the current user
        $cart = Cart::where('user_id', auth()->id())->first();

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart not found.'], 404);
        }

        // Retrieve the cart items with product details
        $cartItems = $cart->items()->with('product')->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Cart is empty.'], 400);
        }

        DB::beginTransaction();

        try {
            // Create a new order
            $order = new Order();
            $order->user_id = auth()->id();
            $order->status = 'chờ xác nhận'; // Set the order status to pending
            $order->save();

            foreach ($cartItems as $item) {
                $product = $item->product;

                if ($product) {
                    // Ensure the product has sufficient stock
                    if ($product->quantity < $item->quantity) {
                        throw new Exception("Insufficient stock for product {$product->name}");
                    }
                    
                    // Update the product's quantity and sold fields
                    $product->quantity -= $item->quantity; // Reduce stock
                    $product->sold += $item->quantity;     // Increase sold
                    $product->save();
                }

                // Save each cart item to the order_items table
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $product->price, // Save the price at the time of order
                ]);
            }

            // Clear the cart items
            $cart->items()->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Cart cleared, order created, and products updated successfully.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
    public static function getShopItemCount()
    {
        $product = Product::where('seller_id', auth()->id())
                    ->get();

        Log::info($product ? $product : 'No items found');

        return $product ? $product->sum('quantity') : 0;
    }
}
