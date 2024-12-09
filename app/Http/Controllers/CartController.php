<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Payment;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Http\Request;
use Exception;

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
        DB::beginTransaction();

        try {
            // Extract payment details from the request
            $paymentData = $request->only([
                'vnp_Amount',
                'vnp_BankCode',
                'vnp_OrderInfo',
                'vnp_TmnCode',
                'vnp_TransactionNo',
                'vnp_TransactionStatus',
                'vnp_TxnRef',
                'vnp_PayDate',
            ]);

            // Save payment details to the payments table
            $payment = new Payment();
            $payment->transaction_id = $paymentData['vnp_TransactionNo'] ?? null;
            $payment->user_id = auth()->id();
            $payment->money = $paymentData['vnp_Amount'] / 100; // Convert to actual amount (assuming VND is in smallest units)
            $payment->note = $paymentData['vnp_OrderInfo'] ?? null;
            $payment->code_vnpay = $paymentData['vnp_TmnCode'] ?? null;
            $payment->code_bank = $paymentData['vnp_BankCode'] ?? null;
            $payment->time = $paymentData['vnp_PayDate']
                ? \Carbon\Carbon::createFromFormat('YmdHis', $paymentData['vnp_PayDate'])
                : now();
            $payment->save();

            // Get the cart for the current user
            $cart = Cart::where('user_id', auth()->id())->first();

            if (!$cart || $cart->items->isEmpty()) {
                throw new Exception('Cart not found or is empty.');
            }

            // Group cart items by seller
            $cartItemsBySeller = $cart->items->groupBy(function ($item) {
                return $item->product->seller_id; // Assuming each product has a `seller_id`
            });

            // Create an order for each seller
            foreach ($cartItemsBySeller as $sellerId => $sellerItems) {
                $order = new Order();
                $order->user_id = auth()->id();
                $order->status = 'Chờ xác nhận'; // Set the order status to pending
                $order->save();

                foreach ($sellerItems as $item) {
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

                    // Save purchase data
                    DB::table('purchases')->insert([
                        'user_id' => auth()->id(),
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'purchased_at' => now(),
                    ]);
                }
            }

            // Clear the cart items
            $cart->items()->delete();

            DB::commit();

            return redirect()->route('cart.view')->with('success', 'Thanh toán thành công, đơn hàng của bạn sẽ sớm được giao.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->route('cart.view')->with('error', $e->getMessage());
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

    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:vnpay,momo',
            'cartTotal' => 'required|numeric'
        ]);
        $cartTotal = $request->cartTotal;
        log::info($cartTotal);
        $paymentMethod = $request->payment_method;

        if ($paymentMethod === 'vnpay') {
            return view('payment.vnpay.index')->with('cartTotal', $cartTotal); // Return VNPay payment view
        }

        if ($paymentMethod === 'momo') {
            return view('payment.momo')->with('cartTotal', $cartTotal); // Return Momo payment view
        }

        return back()->withErrors(['payment_method' => 'Invalid payment method selected.'])->with('cartTotal');
    }

}