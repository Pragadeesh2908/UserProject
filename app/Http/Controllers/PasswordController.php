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

        $email = $request->email;

        DB::table('users')->where('email', $email)->update(['password_reset' => now()]);

        $user = User::where('email', $email)->first();

        Mail::send('email.forgotPassword', ['user' => $user], function ($message) use ($email) {
            $message->to($email);
            $message->subject('Reset Password');
        });

        return back()->with('message', 'We have emailed you a reset password link');
    }

    public function resetPassword($email) 
    {
        $user = DB::table('users')->where('email', $email)->first();
        if ($user->password_reset != null && Carbon::now()->diffInMinutes($user->password_reset) <= 10) {
            return view('linkPage', ['email' => $email]);
        } else {
            return redirect()->route('forgotPassword')->with('error', 'The password reset link is invalid or expired!');
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

        DB::table('users')->where('email', $request->input('email'))->update(['password_reset' => null]);

        return redirect('/')->with('message', 'Your password has been changed!');
    }
}
