<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Mail\SendOtpMail;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function showLoginForm()
    {
        return view('login');
    }

    public function showOtpForm()
    {
        return view('auth.otp');
    }

    public function logincheck(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }
        $user = Auth::user();

        // Generate and save OTP
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // Send OTP via email
        Mail::to($user->email)->send(new SendOtpMail($otp));
        session(['otp_user_id' => $user->id]);
        Session::save();
        Auth::logout();
        return redirect()->route('otp.form')->with('message', 'We have sent a 6-digit verification code to your ' . $user->email . ' Please enter it below to continue');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);
        $userId = session('otp_user_id');
        $user = \App\Models\User::find($userId);
        if (!$user || $user->otp !== $request->otp || now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }
        // Clear OTP
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();
        Auth::login($user);
        session()->forget('otp_user_id');
        return redirect('index')->with('success', 'Logged in successfully!');
    }

    public function resendOtp(Request $request)
    {
        // Check if OTP session exists
        $userId = session('otp_user_id');

        if (!$userId) {
            return back()->withErrors(['otp' => 'Session expired. Please login again.']);
        }

        // Retrieve user
        $user = User::find($userId);
        if (!$user) {
            return back()->withErrors(['otp' => 'User not found. Please login again.']);
        }

        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        try {
            Mail::to($user->email)->send(new \App\Mail\SendOtpMail($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => 'Failed to send OTP. Please try again.']);
        }

        return back()->with('message', 'A new OTP has been sent to your email.');
    }
}
