<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DoctorForgotPasswordController extends Controller
{
    // ── Step 1: Show email form ───────────────────────────────────────────
    public function showForm()
    {
        return view('parklane.doctor.forgot_password');
    }

    // ── Step 2: Send reset link ───────────────────────────────────────────
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $doctor = Doctor::where('email', $request->email)->first();

        // Always return success to prevent email enumeration
        if (!$doctor) {
            return back()->with('success', 'If that email exists, a reset link has been sent.');
        }

        $token = Str::random(64);

        $doctor->password_reset_token      = $token;
        $doctor->password_reset_expires_at = now()->addMinutes(60);
        $doctor->save();

        // Send email
        Mail::to($doctor->email)->send(
            new \App\Mail\DoctorPasswordResetMail($doctor, $token)
        );

        return back()->with('success', 'A password reset link has been sent to your email.');
    }

    // ── Step 3: Show new password form ───────────────────────────────────
    public function showResetForm(Request $request, $token)
    {
        $doctor = Doctor::where('password_reset_token', $token)
            ->where('password_reset_expires_at', '>', now())
            ->first();

        if (!$doctor) {
            return redirect()->route('doctor.login')
                ->with('error', 'This reset link is invalid or has expired. Please request a new one.');
        }

        return view('parklane.doctor.reset_password', compact('token', 'doctor'));
    }

    // ── Step 4: Save new password ─────────────────────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $doctor = Doctor::where('password_reset_token', $request->token)
            ->where('password_reset_expires_at', '>', now())
            ->first();

        if (!$doctor) {
            return redirect()->route('doctor.login')
                ->with('error', 'This reset link is invalid or has expired.');
        }

        $doctor->password                  = Hash::make($request->password);
        $doctor->password_reset_token      = null;
        $doctor->password_reset_expires_at = null;
        $doctor->password_set              = true;
        $doctor->save();

        return redirect()->route('doctor.login')
            ->with('success', 'Password reset successfully. You can now log in.');
    }
}