<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
                return redirect()->route('account.home');
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
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'customer';
            $user->confirm_otp = rand(100000, 999999);
            $user->token_forgot = '';
            $user->save();

            return redirect()->route('account.login')->with('success', 'You have registed successfully.');
        } else {
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }

    }

    public function home() {
        return view('home');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function profile() {
        return view('account.profile');
    }
}
