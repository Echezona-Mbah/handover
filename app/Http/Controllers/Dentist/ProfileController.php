<?php

namespace App\Http\Controllers\Dentist;

use App\Http\Controllers\Controller;
use App\Models\DentistDepartment;
use App\Models\DentistRole;
use App\Models\DentistUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $departments = DentistDepartment::all();
        $roles = DentistRole::all();
        $DentistUnits = DentistUnit::all();


        return view('parklane.dentist.profile',compact('departments','roles','DentistUnits'));
    }

    public function updateProfile(Request $request)
    {
        // dd($request->all());
        $dentist = Auth::guard('dentist')->user();
   
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|max:255', // You may want to remove this if email is read-only
            'phone_number' => 'required|string|max:255',
            'dentist_department_id' => 'nullable',
            'dentist_role_id' => 'nullable',
             'dentist_unit_id' => 'nullable',
            'ages' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);
       // dd($request->all());
        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($dentist->profile_image) {
                Storage::delete($dentist->profile_image);
            }
            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $dentist->profile_image = $path;
        }
    
        // Update doctor profile
        $dentist->update([
            'full_name' => $request->full_name,
            'ages' => $request->ages,
            'gender' => $request->gender,
            'dentist_department_id' => $request->dentist_department_id,
            'dentist_role_id' => $request->dentist_role_id,
            'dentist_unit_id' => $request->dentist_unit_id,
            'phone_number' => $request->phone_number,
            'address' => $request->address,

        ]);
    
        return back()->with('success', 'Profile updated successfully!');
    }
    
}
