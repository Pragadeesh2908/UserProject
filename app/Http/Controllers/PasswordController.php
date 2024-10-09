<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function showUpdateForm()
    {
        return view('update');
    }
    public function update(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check if the current password is correct
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();


        return redirect()->route('profile')->with('success', 'Password updated successfully.');
    }
    public function forgotPassword()
    {
        return view('forgotPasswordPage');
    }

    public function submitForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);
        $email = $request->input('email');
        $token = Str::random(64);
        $expiresAt = Carbon::now()->addMinutes(10);

        $email_exists = DB::table('password_resets')->where('email', $email)->exists();
        $expire_time= DB::table('password_resets')->where('email', $email)->value('expires_at');
        // dd($expire_time);
        if(!$email_exists || ($email_exists && Carbon::now()->greaterThan($expire_time))){
            DB::table('password_resets')->insert([
                'email' => $request->input('email'),
                'token' => $token,
                'created_at' => Carbon::now(),
                'expires_at' => $expiresAt
            ]);
        }
        else{
            return back()->with('info', 'The reset password link is already sent.');
        }
        $user = User::where('email', $request->input('email'))->first();
        if($user){
            
        }
        Mail::send('email.forgotPassword', ['token' => $token, 'user' => $user], function ($message) use ($request) {
            $message->to($request->input('email'));
            $message->subject('Reset Password');
        });

        return back()->with('message', 'we have emailed you reset password link');
    }

    public function resetPassword($token)
    {
        $expires_link = DB::table('password_resets')->where('token', $token)->value('expires_at');

        if (Carbon::now()->greaterThan(Carbon::parse($expires_link))) {
            return redirect('/')->with('error', 'Invalid or expired token!');
        } else {
            return view('linkPage', ['token' => $token]);
        }
    }


    public function submitResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[@$!%*#?&]/',
                'confirmed',
            ],
            'password_confirmation' => 'required',
        ]);



        User::where('email', $request->input('email'))
            ->update(['password' => Hash::make($request->input('password'))]);

        DB::table('password_resets')
            ->where('email', $request->input('email'))
            ->delete();

        return redirect('/')->with('message', 'Your password has been changed!');
    }
}
