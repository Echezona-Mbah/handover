<?php

namespace App\Http\Controllers\Dentist;

use App\Http\Controllers\Controller;
use App\Models\Dentists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        // dd('sdddddddsd');

        $dentist = Dentists::with('shiftSchedules')->find(auth()->guard('dentist')->id());
        // dd($dentist);
        return view('parklane.dentist.dashboard', compact('dentist'));
    }


    public function create()
    {
        return view('parklane.dentist.login');
    }

    public function dentistLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::guard('dentist')->attempt($credentials)) {
            $request->session()->regenerate();
            //  dd('sdsd');
            return redirect()->route('dentist.dashboard'); 
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
