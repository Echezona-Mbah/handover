<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
    
        $request->session()->regenerate();
    
        // Get the logged-in user
        $user = Auth::user();

        //dd($user);
    
        // Check the hospital and redirect accordingly
        if ($user->hospital_id == 1) {
            return redirect()->route('dashboard');
        } elseif ($user->hospital_id == 2) {
            return redirect()->route('hospital2.dashboard');
        } else {
            return redirect()->route('error.page')->with('error', 'Invalid hospital ID.');
        }
    }

    // public function doctorLogin(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:8',
    //     ]);



    //     if (Auth::guard('doctor')->attempt($credentials)) {
    //         $request->session()->regenerate();
    //         // print_r( $request->session()->regenerate());die();


    //         $doctor = Auth::guard('doctor')->user();

    //         // Redirect based on hospital_id
    //         if ($doctor->hospital_id == 1) {
    //             return redirect()->route('doctor.dashboard');
    //         } elseif ($doctor->hospital_id == 2) {
    //             return redirect()->route('hospital2.dashboard');
    //         } else {
    //             return redirect()->route('error.page')->with('error', 'Invalid hospital ID.');
    //         }
    //     }

    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ]);
    // }
    

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
