<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Auth;
 class AccountController extends Controller
{
    //Log out
    public function login(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email', // Validate email
            'password' => 'required|string|min:3', // Validate password
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect('/login')
                ->withErrors($validator) // Pass validation errors
                ->withInput(); // Keep the old input
        }

        $email = $request->input('email');
        $password = $request->input('password');

        // Find the user by email
        $user = DB::table('users')->where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            // Password is correct, log the user in
            // You can use Laravel's authentication here if needed
            return redirect('/home');
        
        } else {
            // Invalid credentials, flash an error message and redirect back
            return redirect('/login')
                ->withErrors(['credentials' => 'Invalid email or password.']) // Custom error message
                ->withInput(); // Keep the old input
        }
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validate the image
        ]);

        $user = Auth::user();
        $image = $request->file('avatar');
        $fileName = $user->id . '.jpg'; // Rename to userID.jpg
        $folderPath = 'frontend/img/account/';
        $filePath = $folderPath . $fileName; // Full path to store in the database

        // Move the file to the specified directory
        $image->move(public_path($folderPath), $fileName);

        // Update the avatar field in the database with the file path
        $user->avatar = $filePath;
        $user->save();

        return response()->json([
            'success' => true,
            'avatar' => $filePath, // Return the updated path for frontend use
        ]);
    }

    public function register(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email', // Validate email
            'name' => 'required|string|max:255', // Validate name
            'password' => 'required|string|min:3', // Validate password with confirmation
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect('/register')
                ->withErrors($validator) // Pass validation errors
                ->withInput(); // Keep the old input
        }

        // Create a new user instance if validation passes
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        // Flash success message and redirect to login page
        session()->flash('success', 'Registration successful. Please login.');

        // Redirect to login page
        return redirect('/login');
        }
    }

    

?>
