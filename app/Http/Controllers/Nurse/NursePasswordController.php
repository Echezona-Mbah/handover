<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Nurses;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class NursePasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('parklane.nurses.forgot_password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::broker('nurses')->sendResetLink($request->only('email'));
        if ($status === Password::RESET_THROTTLED) {
            return back()->withErrors(['email' => 'Please wait before requesting another password link.']);
        }

        if ($status !== Password::RESET_LINK_SENT && $status !== Password::INVALID_USER) {
            return back()->withErrors(['email' => 'The password reset link could not be sent. Please try again.']);
        }

        return back()->with('success', 'If a nurse account exists for that email, a password setup link has been sent.');
    }

    public function showResetForm(string $token)
    {
        return view('parklane.nurses.reset_password', [
            'token' => $token,
            'email' => request()->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $credentials = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::broker('nurses')->reset(
            $credentials,
            function (Nurses $nurse, string $password): void {
                $nurse->forceFill([
                    'password' => Hash::make($password),
                ])->save();
                event(new PasswordReset($nurse));
            },
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('nurse.login')
                ->with('success', 'Your password has been set. You can now log in.');
        }

        return redirect()->route('nurse.password.reset', [
            'token' => $credentials['token'],
            'email' => $credentials['email'],
        ])->withErrors(['email' => 'This password setup link is invalid or has expired. Request a new one.']);
    }
}
