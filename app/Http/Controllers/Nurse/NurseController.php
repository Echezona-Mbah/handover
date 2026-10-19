<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use App\Models\NurseDepartment;
use App\Models\Patient;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NurseController extends Controller
{
    public function editProfile()
    {
        $nurse = Auth::guard('nurse')->user();
        $hospitalId = $nurse->hospital_id;

        $wards = Ward::where('hospital_id', $hospitalId)->get();
        $departments = NurseDepartment::all();

        return view('parklane.nurses.profile', compact('wards', 'departments'));
    }

    public function updateProfile(Request $request)
    {
        $nurse = Auth::guard('nurse')->user();
    
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required',
            'ages' => 'required|date',
            'gender' => 'required|in:male,female',
            // 'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            // 'years_of_experience' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:20',
            'qualification' => 'nullable|string',
            'blood_group' => 'required|string',
            'address' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'ward_id' => 'nullable|exists:wards,id',
            'nurse_department_id' => 'nullable|exists:nurse_departments,id',
            'profile_picture' => 'nullable|image|max:2048',
        ]);
    
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($nurse->profile_image) {
                Storage::delete($nurse->profile_image);
            }
            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $nurse->profile_image = $path;
        }
            // dd($request->full_name);
        // Update doctor profile
        $nurse->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'ages' => $request->ages,
            'gender' => $request->gender,

            'license_number' => $request->license_number,
            'phone_number' => $request->phone_number,
            'qualification' => $request->qualification,
            'blood_group' => $request->blood_group,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'ward_id'=> $request->ward_id,
            'nurse_department_id'=> $request->nurse_department_id,

        ]);
    
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function index()
    {
     
        return view('parklane.nurses.availability',);
    }

    public function update(Request $request)
    {
        $doctor = Auth::guard('nurse')->user(); // Get the logged-in doctor
    
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
