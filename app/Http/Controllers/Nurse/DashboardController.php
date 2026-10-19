<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Nurses;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function indexNurse()
    {
        $nurse = Auth::guard('nurse')->user();

        $totalPatients = Patient::where('ward_id', $nurse->ward_id)
            ->where('hospital_id', $nurse->hospital_id)
            ->count();
        $totalNurses = Nurses::where('ward_id', $nurse->ward_id)
            ->where('hospital_id', $nurse->hospital_id)
            ->count();
        return view('parklane.nurses.dashboard', compact('nurse', 'totalPatients','totalNurses'));
    }
    
    



    public function createNurse()
    {
        return view('parklane.nurses.nurse_login');
    }

    public function nurseLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        if (Auth::guard('nurse')->attempt($credentials)) {
            $request->session()->regenerate();
            //  dd('kksdsd');
            return redirect()->route('nurse.dashboard'); 
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
