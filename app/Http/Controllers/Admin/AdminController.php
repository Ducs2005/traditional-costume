<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function checkAuth()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if the user has the role of admin
            $user = Auth::user();
            if ($user->role === 'admin') { // Adjust the role check according to your implementation
                return redirect()->route('admin.dashboard');
            } else {
                // If not an admin, redirect to home or an unauthorized page
                return redirect()->route('admin.login')->with('error', 'Please log in to access this page.');
            }
        }

        // If not authenticated, redirect to login page
        return redirect()->route('admin.login')->with('error', 'Please log in to access this page.');
    }

    public function showLogin()
    {
        return view('admin_views.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Check if the authenticated user has an admin role
            if (Auth::user()->role === 'admin') { // Adjust this condition based on your role structure
                return redirect()->intended('admin/dashboard')->with('success', 'You are logged in!');
            }

            // Log the user out if they are not an admin
            Auth::logout();
            return back()->withErrors([
                'email' => 'Access denied. You do not have administrator privileges.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('success', 'Logged out successfully!');
    }
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        // Optionally delete the image file if stored on disk
       // if (File::exists($product->img_path)) {
       //     File::delete($product->img_path);
       // }
    

        $product->delete();

        return redirect()->route('table.product')->with('success', 'Product deleted successfully!');
    }
    public function openShop()
    {
        $waitingUsers = User::where('selling_right', 'waiting')->get();
        return view('admin_views.openShop', compact('waitingUsers'));
    }

    
    public function create()
    {
        return view('admin_views.index');  
    }
    public function showUsers(Request $request)
    {
        $query = User::query();
        
        // Get the logged-in user's ID
        $loggedInUserId = auth()->user()->id;

        // If the search parameter is present and not empty, filter by name or email
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%");
        }

        // Exclude the logged-in user from the results
        $users = $query->where('id', '!=', $loggedInUserId)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin_views.user', compact('users'));
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the input fields
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'selling_right' => 'nullable|string|max:255',
        ]);

        // Update the user's details
        $user->update($validated);

        // Redirect to the user list
        return redirect()->route('table.user')->with('success', 'User updated successfully.');
    }

    public function updateProduct(Request $request)
    {
        Log::info($request->id);
       try {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = Product::findOrFail($request->product_id);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            // Delete old image
            $oldImagePath = public_path('frontend/img/product/' . basename($product->img_path));
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            // Store the new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('frontend/img/product'), $imageName);
            $product->img_path = 'frontend/img/product/' . $imageName;
        }
        $product->save();

        return redirect()->route('table.product')->with('success', 'Product updated successfully!');
       }
       catch (Exception $e)
       {
        Log::error($e->errors());
        return back()->withErrors($e->errors())->withInput();
       }
    }


    public function showProducts(Request $request)
    {


        $products = Product::all();

        return view('admin_views.product', compact('products'));
    }
    public function showOrder(Request $request)
    {
        $query = Order::query();
    
        // Apply status filter if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
    
        // Get orders with related items and products
        $orders = $query->with(['items.product'])->get();
        $orderTotals = $orders->mapWithKeys(function ($order) {
            $total = $order->items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
    
            return [$order->id => $total];
        });
        // Transform orders to include items and their products
        $ordersWithProducts = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'status' => $order->status,
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'quantity' => $item->quantity,
                        'product' => $item->product, // Include the related product
                    ];
                }),
            ];
        });
    
        // Pass the transformed data to the view
        return view('admin_views.order', compact('orders', 'orderTotals'));
    }
    
    public function getUserDetails($id)
    {
        // Find the user
        try {
            $user = User::findOrFail($id);
        // Assuming you have address ID details in the user's address
        $address = Address::find($user->address_id);
        Log::info($address);

        // Combine user and external address data
        if ($address)
        {
            $userData = [
                'user' => $user,
                'address' => [
                    'province' => $address->province,
                    'district' => $address->district,
                    'ward' => $address->ward,
                ]
            ];
        }
        else{
            $userData = [
                'user' => $user,
            ];
        }

        // Return user data along with geographic names
        return response()->json($userData);
        }
        catch (Exception $e) {
            $reponse = [
                'error'=> $e->getMessage(),
            ];
            return response()->json($reponse);
        }
    }

    public function acceptSellingRight($userId)
    {
        try {

            $user = User::findOrFail($userId);
            $user->selling_right = 'yes'; // Set selling right to accepted
            $user->save();

            // Send a notification to the user
            $this->sendNotification($user, 'Yêu cầu bán sản phẩm đã được phê duyệt.');

            return response()->json(['message' => 'Selling right granted and notification sent.']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Method to reject the selling right
    public function rejectSellingRight($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->selling_right = 'no'; // Set selling right to rejected
            $user->save();

            // Send a notification to the user
            $this->sendNotification($user,  'Yêu cầu bán sản phẩm bị từ chối');

            return response()->json(['message' => 'Selling right revoked and notification sent.']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Helper method to send notifications
    protected function sendNotification($user, $message)
    {
        Notification::create([
            'sender' => 'Quản trị viên',
            'receiver_id' => $user->id,
            'content' => $message,
            'receiver_type' => 'one'
        ]);

        // You could also send an email, or use a notification channel (like Pusher, SMS, etc.) here
    }
    


}
