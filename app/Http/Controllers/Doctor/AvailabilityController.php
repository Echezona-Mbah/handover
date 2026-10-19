<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function index()
    {
     
        return view('parklane.doctor.availability',);
    }

    public function update(Request $request)
    {
        $doctor = Auth::guard('doctor')->user(); // Get the logged-in doctor
    
        $request->validate([
            'sun_start' => 'required|string|max:255',
            'sun_end' => 'required|string|max:255',
            'mon_start' => 'required|string|max:255',
            'mon_end' => 'required|string|max:255',
            'tue_start' => 'required|string|max:255',
            'tue_end' => 'required|string|max:255',
            'wed_start' => 'nullable|string|max:255',
            'wed_end' => 'nullable|string|max:255',
            'thu_start' => 'required|string|max:255',
            'thu_end' => 'required|string|max:255',
            'fri_start' => 'required|string|max:255',
            'fri_end' => 'required|string|max:255',
            'sat_start' => 'required|string|max:255',
            'sat_end' => 'required|string|max:255',
        ]);
    
        // Update doctor availability
        $doctor->update([
            'sun_start' => $request->sun_start,
            'sun_end' => $request->sun_end,
            'mon_start' => $request->mon_start,
            'mon_end' => $request->mon_end,
            'tue_start' => $request->tue_start,
            'tue_end' => $request->tue_end,
            'wed_start' => $request->wed_start,
            'wed_end' => $request->wed_end,
            'thu_start' => $request->thu_start,
            'thu_end' => $request->thu_end,
            'fri_start' => $request->fri_start,
            'fri_end' => $request->fri_end,
            'sat_start' => $request->sat_start,
            'sat_end' => $request->sat_end,
        ]);
    
        return back()->with('success', 'Availability updated successfully.');
    }
    

}
