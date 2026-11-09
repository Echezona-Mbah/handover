<?php

namespace App\Http\Controllers\Dentist;

use App\Http\Controllers\Controller;
use App\Models\Dentists;
use App\Models\News;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        // dd('sdddddddsd');

        $dentist = Dentists::with([
            'shiftSchedules',
            'DentistDepartment',
            'DentistRole',
            'hospitals',
        ])->findOrFail(auth()->guard('dentist')->id());

        $assignedPatients = Patient::where('hospital_id', $dentist->hospital_id)
            ->where(function ($patients) use ($dentist) {
                $patients->where('dentist_assign', $dentist->id);
                if ($dentist->dentist_unit_id) {
                    $patients->orWhere('dentist_unit_id', $dentist->dentist_unit_id);
                }
            });
        $patientCount = (clone $assignedPatients)->count();
        $urgentPatientCount = (clone $assignedPatients)
            ->whereIn('urgency_status', ['urgent', 'emergency'])
            ->count();
        $recentActivity = $dentist->notifications()->latest()->limit(6)->get();
        $unreadActivityCount = $dentist->unreadNotifications()->count();
        $hospitalUpdates = News::where('hospital_id', $dentist->hospital_id)
            ->latest()
            ->limit(4)
            ->get();

        return view('parklane.dentist.dashboard', compact(
            'dentist',
            'patientCount',
            'urgentPatientCount',
            'recentActivity',
            'unreadActivityCount',
            'hospitalUpdates'
        ));
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
