<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorDepartment;
use App\Models\DoctorUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $departments = DoctorDepartment::all();
        $units = DoctorUnit::all();


        return view('parklane.doctor.profile',compact('departments','units'));
    }

    public function updateProfile(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();
   
// dd($request->all());    
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|max:255', // You may want to remove this if email is read-only
            'phone_number' => 'required|string|max:255',
            'department_id' => 'nullable',
            'doctor_unit_id' => 'nullable',
            'doctor_cardie' => 'nullable',
            'gender' => 'required|in:male,female',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);
       // dd($request->all());
        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($doctor->profile_image) {
                Storage::delete($doctor->profile_image);
            }
            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $doctor->profile_image = $path;
        }
    
        // Update doctor profile
        $doctor->update([
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'doctor_departments' => $request->department_id,
            'doctor_unit_id' => $request->doctor_unit_id,
            'phone_number' => $request->phone_number,
            'doctor_cardie' => $request->doctor_cardie,
        ]);
    
        return back()->with('success', 'Profile updated successfully!');
    }
    
}
