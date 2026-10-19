<?php

namespace App\Http\Controllers\Dentist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function index()
    {
     
        return view('parklane.dentist.availability',);
    }

    
    public function update(Request $request)
    {
        $dentist = Auth::guard('dentist')->user();
    
        $days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
    
        $rules = [];
        foreach ($days as $day) {
            $rules["{$day}_start"] = 'nullable|string|max:10';
            $rules["{$day}_end"] = 'nullable|string|max:10';
        }
    
        $request->validate($rules);
    
        $availability = [];
        foreach ($days as $day) {
            $start = $request->input("{$day}_start");
            $end = $request->input("{$day}_end");
    
            $availability["{$day}_start"] = in_array($start, ['From', null, '']) ? null : $start;
            $availability["{$day}_end"] = in_array($end, ['To', null, '']) ? null : $end;
        }
    
        $dentist->update($availability);
    
        return back()->with('success', 'Availability updated successfully.');
    }
    
}
