<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DoctorPasswordSetupController extends Controller
{
    public function show($token)
    {
        $doctor = Doctor::where('password_reset_token', $token)
            ->where('password_reset_expires_at', '>', now())
            ->first();

        if (!$doctor) {
            return redirect()->route('doctor.login')->with('error', 'This password setup link is invalid or has expired. Please contact your administrator.');
        }

        return view('parklane.doctor.doctor_password_setup', compact('doctor', 'token'));
    }

    public function store(Request $request, $token)
    {
        $doctor = Doctor::where('password_reset_token', $token)
            ->where('password_reset_expires_at', '>', now())
            ->first();

        if (!$doctor) {
            return redirect()->route('doctor.login')->with('error', 'This password setup link is invalid or has expired. Please contact your administrator.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $doctor->password = Hash::make($request->password);
        $doctor->password_reset_token = null;
        $doctor->password_reset_expires_at = null;
        $doctor->password_set = true;
        $doctor->save();

        return redirect()->route('doctor.login')->with('success', 'Password set successfully. You can now log in.');
    }





     public function showss()
    {
        return view('parklane.doctor.doctor_change_password');
    }

    public function storess(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $doctor = Auth::guard('doctor')->user();

        // Verify current password
        if (!Hash::check($request->current_password, $doctor->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Prevent reusing same password
        if (Hash::check($request->password, $doctor->password)) {
            return back()->withErrors(['password' => 'New password must be different from your current password.']);
        }

        $doctor->password                  = Hash::make($request->password);
        $doctor->password_reset_token      = null;
        $doctor->password_reset_expires_at = null;
        $doctor->password_set              = true;
        $doctor->save();

        return back()->with('success', 'Password changed successfully.');
    }








}