<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;


class DashboardController extends Controller
{

public function index()
{
    $doctor = Doctor::with([
        'shiftSchedules',
        'doctorDepartment',
        'doctorUnit',
        'subUnit',
        'hospitals',
    ])->findOrFail(auth()->guard('doctor')->id());

    $patientCount = Patient::where('doctor_unit_id', $doctor->doctor_unit_id)->count();

    // Count of invites this doctor's unit/sub-unit has RECEIVED —
    // i.e. how many times another unit has invited them to collaborate
    // on a patient. Mirrors the sub-unit-takes-precedence convention
    // used throughout PatientController.
    $invitedUnitsQuery = \App\Models\PatientUnitInvite::query();

    if (!empty($doctor->doctor_sub_unit_id)) {
        $invitedUnitsQuery->where('doctor_sub_unit_id', $doctor->doctor_sub_unit_id);
    } else {
        $invitedUnitsQuery->where('doctor_unit_id', $doctor->doctor_unit_id)
            ->whereNull('doctor_sub_unit_id');
    }

    $invitedUnitsCount = $invitedUnitsQuery->count();

    return view('parklane.doctor.dashboard', compact('doctor', 'patientCount', 'invitedUnitsCount'));
}

    



    public function create()
    {
        return view('parklane.doctor.login');
    }

    public function doctorLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::guard('doctor')->attempt($credentials)) {
            $request->session()->regenerate();
            // dd('sdsd');
            return redirect()->route('doctor.dashboard'); 
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function clearCache()
{
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');

    return response()->json(['message' => 'Cache cleared successfully!']);
}


public function logout(Request $request)
{
    Auth::guard('doctor')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('doctor.login');
}
}
