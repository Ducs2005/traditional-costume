<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Orderitem;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Fetch the user's orders
        try {
            $query = Order::where('user_id', auth()->id()); // Assuming the user is logged in

            // Check if a status filter is applied and modify the query
            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            // Get the orders (filtered or all)
            $orders = $query->get();

            // Initialize arrays for order items and their total price
            $orderItems = [];
            $orderTotals = []; // To store the total for each order

            // Loop through each order to get the associated items, products, and their sellers
            foreach ($orders as $order) {
                // Fetch order items with product details and the seller associated with each product
                $items = $order->items()->with(['product', 'product.seller'])->get(); // Fetch product and seller info
                $orderItems[$order->id] = $items;

                // Calculate the total for this order
                $orderTotal = $items->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                });

                // Store the total price for each order
                $orderTotals[$order->id] = $orderTotal;
            }
        } catch (Exception $e)
        {
            log::info($e->getMessage());
        }

        // Pass the orders, order items, and total prices to the view
        return view('view_orders', compact('orders', 'orderItems', 'orderTotals'));
    }

    public function seller_view(Request $request)
    {
        // Get the current logged-in user's ID
        $userId = auth()->user()->id;

        // Get the filter status from the request (default to 'all' if not provided)
        $statusFilter = $request->input('status', 'all');
        
        // Get all order items where the product's seller_id matches the current user's ID
        $orderItems = OrderItem::whereHas('product', function ($query) use ($userId) {
            $query->where('seller_id', $userId);
        })
        ->get();

        // Get the related orders for those order items
        $orderIds = $orderItems->pluck('order_id');

        // Fetch the orders that correspond to those order items
        // If a status filter is applied, add it to the query
        $ordersQuery = Order::whereIn('id', $orderIds)->with('user');

        if ($statusFilter !== 'all') {
            $ordersQuery->where('status', $statusFilter);
        }

        $orders = $ordersQuery->get();

        // Calculate the total price for each order (if needed)
        $orderTotals = [];
        foreach ($orders as $order) {
            // Fetch order items with product details
            $items = $order->items()->with('product')->get();
            $orderItems[$order->id] = $items;

            // Calculate the total for this order
            $orderTotal = $items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // Store the total price for each order
            $orderTotals[$order->id] = $orderTotal;
        }

        // Pass the orders, order items, and total prices to the view
        return view('view_orders_seller_side', compact('orders', 'orderItems', 'orderTotals', 'statusFilter'));
    }

    

    public function getOrder($orderId)
    {
        // Fetch the order with its items and associated products
        $order = Order::with('items.product.seller')->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json([
            'order' => [
                'id' => $order->id,
                'created_at' => $order->created_at,
                'status' => $order->status,
                'total_price' => $order->items->sum(fn($item) => $item->product->price * $item->quantity),
                'items' => $order->items->map(function ($item) {
                    return [
                        'quantity' => $item->quantity,
                        'product' => [
                            'name' => $item->product->name,
                            'img_path' => $item->product->img_path,
                            'price' => $item->product->price,
                            'seller' => [
                                'name' => $item->product->seller->name, // Seller name
                                'email' => $item->product->seller->email, // Seller email
                            ]
                        ]
                    ];
                })
            ]
        ]);
    }


    public function getOrderSeller($orderId)
    {
        $order = Order::with('items.product')->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        $sellerId = auth()->user()->id;
        $filteredItems = $order->items->filter(function ($item) use ($sellerId) {
            return $item->product && $item->product->seller_id == $sellerId;
        });

        return response()->json([
            'order' => [
                'id' => $order->id,
                'created_at' => $order->created_at,
                'status' => $order->status,
                'total_price' => $filteredItems->sum(fn($item) => $item->product->price * $item->quantity),
                'items' => $filteredItems->map(function ($item) {
                    return [
                        'quantity' => $item->quantity,
                        'product' => [
                            'id' => $item->product->id,
                            'name' => $item->product->name,
                            'img_path' => $item->product->img_path,
                            'price' => $item->product->price
                        ]
                    ];
                })
            ]
        ]);
    }


    public function confirm(Request $request, Order $order)
    {
        try {
            $order->update(['status' => 'Đang giao']);
        }
        catch (Exception $e)
        {
            Log::info('errro' . $e->getMessage());
        }
        return response()->json([
            'message' => 'Đơn hàng đã được xác nhận và chuyển sang trạng thái Đang giao.'
        ]);
    }

    public function confirmReceived(Request $request, Order $order)
    {
        try {
            $order->update(['status' => 'Đã nhận']);
        }
        catch (Exception $e)
        {
            Log::info('errro' . $e->getMessage());
        }
        return response()->json([
            'message' => 'Đơn hàng đã hoàn thành'
        ]);
    }

    public function cancel(Request $request, Order $order)
    {
        $order->update(['status' => 'Đã hủy']);

        return response()->json([
            'message' => 'Đơn hàng đã được hủy thành công.'
        ]);
    }



}
