<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function forgotpass() {
        return view('account.forgot_password');
    }

    public function reset_pwd(Request $request) {
        $data = $request->all();
        $now = Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y');
        $title_mail = "Reset password!".'-'.$now;
        $user = User::where('email','=',$data['email_reset'])->get();
        foreach($user as $key => $value) {
            $user_id = $value->id;
        }
        if($user) {
            $count_user = $user->count();
            if($count_user==0) {
                return redirect()->back()->with('error', 'Email is not registered');
            } else {
                $token = Str::random(25);
                $user = User::find($user_id);
                $user->token_forgot = $token;
                $user->save();

                //send mail
                $to_email = $data['email_reset'];
                $link_reset_pass = url('/new_pwd?email=' .$to_email. '&token='.$token);
                $data = array("name"=>$title_mail, "body"=>$link_reset_pass, 'email'=>$data['email_reset']);

                Mail::send('account.new_password', ['data'=>$data], function($message) use ($title_mail,$data) {
                    $message->to($data['email'])->subject($title_mail);
                    $message->from($data['email'], $title_mail);
                });

                return redirect()->back()->with('message', 'Email sent successfully. Please check your email to reset your password.');
            }
        }
    }

    public function new_pwd(Request $request) {
        return view('account.newPwd');
    }

    public function reset_new_pwd(Request $request) {
        $data = $request->all();
        $token = Str::random(25);
        $user = User::where('email','=',$data['email'])->where('token_forgot', '=', $data['token'])->get();
        $count = $user->count();

        if($count>0) {
            foreach($user as $key => $users) {
                $user_id = $users->id;
            }
            $reset = User::find($user_id);
            $reset->password = Hash::make($data['new_pwd']);
            $reset->token_forgot = $token;
            $reset->save();
            return redirect()->route('account.login')->with('message', 'Password updated.');

        } else {
            return redirect('forgot_password')->with('error', 'Enter email again.');
        }

    }
}

