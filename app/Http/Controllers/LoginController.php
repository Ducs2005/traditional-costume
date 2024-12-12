<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //This method will show login page for customer
    public function index() {
        return view('account.login');
    }

    // This method will authenticate user
    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();

                if (!$user->hasVerifiedEmail()) {
                    Auth::logout();
                    return redirect()->route('account.login')->with('error', 'The email is not registered.');
                }

                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } else {
                    return redirect()->route('account.home');
                }
            } else {
                return redirect()->route('account.login')->with('error','Either email or password is incorrect.');
            }
        } else {
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }
    }

     //This method will show register page for customer
    public function register() {
        return view('account.register');
    }

    public function processRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            // Tạo người dùng mới
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'customer';
            $user->created_at = Carbon::now();
            $user->save();

            // Gửi email xác minh
            $title_mail = "TrdnCostume";
            $bold_text = "Verify your email!";
            $link_verify_email = url('/verify-email?email=' . $user->email . '&token=' . $user->verify_token);
            $data = [
                'bold_text' => $bold_text,
                'body' => $link_verify_email,
                'email' => $user->email
            ];

            Mail::send('account.view_sendVerifyEmail', ['data' => $data], function($message) use ($title_mail, $data) {
                $message->to($data['email'])->subject($title_mail);
                $message->from(env('MAIL_FROM_ADDRESS'), $title_mail);
            });

            return redirect()->route('account.register')->with('message', 'Please check your email to verify your account.');
        } else {
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }
    }


    public function verifyEmail(Request $request)
    {
        $email = $request->query('email'); // Lấy email từ URL

        $user = User::where('email', $email)->first();
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('account.login')->with('success', 'Your account has been verified successfully. Please login.');

    }

    public function home() {
        $featureProducts = Product::orderBy('created_at', 'desc')
                        ->take(4)
                        ->get();
        $gallery = Product::orderBy('img_path', 'asc')
                        ->take(10)
                        ->get();
        return view('home', compact('featureProducts', 'gallery'));
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function profile() {
        $shopItemCount = CartController::getShopItemCount();
       return view('account.profile', ['shopItemCount' => $shopItemCount]);
    }
}
