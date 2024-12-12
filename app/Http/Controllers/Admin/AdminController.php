<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Button;
use App\Models\Color;
use App\Models\Material;
use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Type;
use App\Models\Cophuc;
use Illuminate\Support\Facades\Http;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Psy\Readline\Hoa\Console;

class AdminController extends Controller
{
    public function checkAuth()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if the user has the role of admin
            $user = Auth::user();
            if ($user->role === 'admin') { // Adjust the role check according to your implementation
                return redirect()->route('admin.accessTime');
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
    public function showPayment()
    {
        $payments = Payment::all();
        return view('admin_views.payment', compact('payments'));
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

    public function sendNotifications()
    {
        $users = User::all();
        return view('admin_views.sendNotification',compact('users'));
    }
    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'receiver_type' => 'required|in:all,one',
            'user_id' => 'nullable|exists:users,id', // Only if receiver_type is "one"
            'content' => 'required|string',
        ]);

        // If the notification is for a specific user
        if ($validated['receiver_type'] == 'one' && $validated['user_id']) {
            $receiverId = $validated['user_id'];
            // Logic to send notification to the specific user
        } else {
            // Logic to send notification to all users
            $receiverId = null; // For "all" users, sender_id can be null or you can choose to send notifications to multiple users.
        }

        // Save the notification in the database
        Notification::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'receiver_id' => $receiverId,
            'receiver_type' => $validated['receiver_type'],
            'sender_id' => auth()->id(), // Admin or current authenticated user
            'sender' => 'Quản trị viên'
        ]);

        return redirect()->back()->with('success', 'Notification sent successfully');
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
    
    public static function getUserDetails($id)
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

    public function acceptSellingRight(Request $request, $userId)
    {
        try {

            $user = User::findOrFail($userId);
            $user->selling_right = 'yes'; // Set selling right to accepted
            $user->save();

            // Send a notification to the user
            $this->sendNotification_user($user, 'Yêu cầu bán sản phẩm đã được phê duyệt.','Yêu cầu bán sản phẩm của bạn đã được phê duyệt, xin chúc mừng,');

            return response()->json(['message' => 'Selling right granted and notification sent.']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Method to reject the selling right
    public function rejectSellingRight(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->selling_right = 'no'; // Set selling right to rejected
            $user->save();

            // Send a notification to the user
            $this->sendNotification_user($user, 'Yêu cầu bán sản phẩm của bạn đã bị từ chối. Ghi chú của quản trị viên: ' . $request->reason,  'Yêu cầu bán sản phẩm bị từ chối');

            return response()->json(['message' => 'Selling right revoked and notification sent.']);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Helper method to send notifications
    public static function sendNotification_user($user, $message, $title)
    {
        Notification::create([
            'title' => $title,
            'sender' => 'Quản trị viên',
            'receiver_id' => $user,
            'content' => $message,
            'receiver_type' => 'one',
            'sender_id' => auth()->user()->id,
        ]);
        Log::info(auth()->user()->id);
        // You could also send an email, or use a notification channel (like Pusher, SMS, etc.) here
    }
    public static function sendNotification_all($message, $title)
    {
        Notification::create([
            'title' => $title,
            'sender' => 'Quản trị viên',
            'content' => $message,
            'receiver_type' => 'all',
            'sender_id' => auth()->user()->id,

        ]);

        // You could also send an email, or use a notification channel (like Pusher, SMS, etc.) here
    }
    public static function sendNotification_user_system($message, $title)
    {
        Notification::create([
            'title' => $title,
            'sender' => 'Hệ thống',
            'content' => $message,
            'receiver_type' => 'all',

        ]);

        // You could also send an email, or use a notification channel (like Pusher, SMS, etc.) here
    }

    public function updateDecorationImages(Request $request)
    {
        $request->validate([
            'img1' => 'nullable|image|mimes:jpg|max:2048',
            'img2' => 'nullable|image|mimes:jpg|max:2048',
            'img3' => 'nullable|image|mimes:jpg|max:2048',
            'img4' => 'nullable|image|mimes:jpg|max:2048',
            'img5' => 'nullable|image|mimes:jpg|max:2048',
            'img6' => 'nullable|image|mimes:jpg|max:2048',
            'img7' => 'nullable|image|mimes:jpg|max:2048',
            'img8' => 'nullable|image|mimes:jpg|max:2048',
        ]);

        // Define the specific names for the images
        $imageNames = [
            'img1' => 'trangphuc1.jpg',
            'img2' => 'trangphuc2.jpg',
            'img3' => 'trangphuc3.jpg',
            'img4' => 'trangphuc4.jpg',
            'img5' => 'trangphuc5.jpg',
            'img6' => 'trangphuc6.jpg',
            'img7' => 'trangphuc7.jpg',
            'img8' => 'trangphuc8.jpg',
        ];

        // Loop through the images and save them with the specified names
        for ($i = 1; $i <= 8; $i++) {
            $imageKey = 'img' . $i;

            if ($request->hasFile($imageKey)) {
                $image = $request->file($imageKey);
                $imageName = $imageNames[$imageKey]; // Get the custom name for the image

                // Store the image directly in the public/frontend/img/home directory
                $destinationPath = public_path('frontend/img/home');
                $image->move($destinationPath, $imageName); // This will replace any existing file with the same name
            }
        }

        return response()->json(['success' => true]);
    }


        
    public function showGallery()
    {
        $colors = Color::all();         // Assuming Color is a model for the color data
        $buttons = Button::all();       // Assuming Button is a model for the button data
        $materials = Material::all();   // Assuming Material is a model for the material data
        $types = Type::all();           // Assuming Type is a model for the type data
        // Pass the data to the view (assuming you're returning a view)
        return view('admin_views.gallery', compact('colors', 'buttons', 'materials', 'types'));
    }

        public function indexCophuc()
    {
        $cophuc = Cophuc::all(); // Retrieve all data from the database
        return view('admin.cophuc.index', compact('cophuc'));
    }

    // Edit 
    public function editCophuc($id)
    {
        $cophuc = Cophuc::findOrFail($id);
        return view('cophuc.edit', compact('cophuc'));
    }

    public function updateCophuc(Request $request, $id)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'detail' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validate image format and size
        ]);

        // Find the Cophuc by its ID
        $cophuc = Cophuc::findOrFail($id);

        // Update the fields
        $cophuc->name = $request->name;
        $cophuc->detail = $request->detail;
        $cophuc->description = $request->description;

        // If a new image is uploaded, handle the file upload
        if ($request->hasFile('image')) {
            // Delete the old image file if it exists
            if (File::exists(public_path($cophuc->image))) {
                File::delete(public_path($cophuc->image));
            }

            // Store the new image
            $path = $request->file('image')->store('images/cophuc');
            $cophuc->image = $path;
        }

        // Save the updated Cophuc record
        $cophuc->save();

        // Redirect back with success message
        return redirect()->route('table.cophuc')->with('success', 'Item updated successfully');
    }

    public function createCophuc(Request $request)
{   
    // Nếu là GET request, trả về view để tạo mới
    return view('cophuc.create');
}

    public function storeCophuc(Request $request)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'detail' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validate image format and size
        ]);
    
        // Create a new Cophuc instance
        $cophuc = new Cophuc();
        $cophuc->name = $request->name;
        $cophuc->detail = $request->detail;
        $cophuc->description = $request->description;
    
        // Handle file upload for the image
        if ($request->hasFile('image')) {
            // Store the image in the "images/cophuc" folder
            $path = $request->file('image')->store('images/cophuc', 'public');
            $cophuc->image = $path;
        }
    
        // Save the new Cophuc record to the database
        $cophuc->save();
    
        // Redirect back with success message
        return redirect()->route('table.cophuc')->with('success', 'Item created successfully');
    }
    
    
        // Delete
        public function destroyCophuc($id)
        {
            $cophuc = Cophuc::findOrFail($id);
            $cophuc->delete();
    
            return redirect()->route('table.cophuc')->with('success', 'Item deleted successfully');
        }
}
