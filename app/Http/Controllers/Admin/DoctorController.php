<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorActivity;
use App\Models\Doctors;
use App\Models\ShiftSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;          // ← add this
use PhpParser\Node\Expr\BinaryOp\ShiftRight;
use App\Models\DoctorDepartment;
use App\Models\DoctorSubUnit;
use App\Models\DoctorUnit;
use Illuminate\Support\Facades\Mail;
use App\Models\DoctorNotification;



class DoctorController extends Controller
{

    public function getUnits($department_id)
    {
        $units = DoctorUnit::where('doctor_department_id', $department_id)
            ->whereNull('parent_id') // only top-level
            ->get();

        return response()->json($units);
    }

    public function getSubUnits($unit_id)
    {
        $subUnits = DoctorSubUnit::where('doctor_unit_id', $unit_id)->get();
        return response()->json($subUnits);
    }
    // public function create()
    // {
    //     $departments = DoctorDepartment::all();
    //     $doctorUnits = \App\Models\DoctorUnit::with('subUnits')->whereNull('parent_id')->get();


    //     return view('parklane.add_doctor',compact('departments','doctorUnits'));
    // }




    public function create()
{
    $user = Auth::user();

    if ($user->is_super_admin) {
        $departments = DoctorDepartment::all();
    } else {
        $departments = DoctorDepartment::where('id', $user->doctor_department_id)->get();
    }

    $doctorUnits = DoctorUnit::with('subUnits')->whereNull('parent_id')->get();

    return view('parklane.add_doctor', compact('departments', 'doctorUnits'));
}





//    public function store(Request $request)
// {
//     $validatedData = $request->validate([
//         'full_name' => 'required|string|max:255',
//         'email' => 'required|email|unique:doctors,email',
//         'department_id' => 'required',
//         'doctor_unit_id' => 'required|exists:doctor_units,id',
//         'doctor_sub_unit_id' => 'nullable|exists:doctor_sub_units,id',
//         'password' => 'required|string|min:8|confirmed',
//         'hospital_id' => 'required|exists:hospitals,id',
//         'cadre' => 'required|in:house_officer,medical_officer,registrar,senior_registrar,consultant',

//     ]);

//     $doctor = Doctor::create([
//         'full_name' => $request->full_name,
//         'email' => $request->email,
//         'doctor_departments' => $request->department_id,
//         'doctor_unit_id' => $request->doctor_unit_id,
//         'doctor_sub_unit_id' => $request->doctor_sub_unit_id ?: null,
//         'hospital_id' => $request->hospital_id,
//         'user_id' => Auth::id(),
//         'password' => Hash::make($request->password),
//         'cadre' => $request->cadre,

//     ]);

//     DoctorNotification::create([
//         'doctor_unit_id' => $doctor->doctor_unit_id,
//         'doctor_sub_unit_id' => $doctor->doctor_sub_unit_id,
//         'department_id' => $doctor->doctor_departments,
//         'hospital_id' => $doctor->hospital_id,
//         'patient_id' => null,
//         'title' => 'Doctor Registered',
//         'message' => 'Your account has been registered successfully.',
//         'type' => 'doctor_registered',
//     ]);

//     Mail::to($doctor->email)->send(new \App\Mail\DoctorRegisteredMail($doctor));

//     return redirect()->route('add_doctor')->with('success', 'Doctor registered successfully!');
// }

public function store(Request $request)
{
    $user = Auth::user();

    $validatedData = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email',
        'department_id' => 'required',
        'doctor_unit_id' => 'required|exists:doctor_units,id',
        'doctor_sub_unit_id' => 'nullable|exists:doctor_sub_units,id',
        'hospital_id' => 'required|exists:hospitals,id',
        'cadre' => 'required|in:house_officer,medical_officer,registrar,senior_registrar,consultant',
         'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $departmentId = $user->is_super_admin
        ? $validatedData['department_id']
        : $user->doctor_department_id;

    if (!$user->is_super_admin && (int) $validatedData['department_id'] !== (int) $user->doctor_department_id) {
        abort(403, 'You can only add doctors to your own department.');
    }

    $existingDoctor = Doctor::where('email', $validatedData['email'])->first();

    if ($existingDoctor) {
        if ((int) $existingDoctor->doctor_departments === (int) $departmentId) {
            return back()
                ->withInput()
                ->with('error', 'This doctor is already registered in your department.');
        }

        $existingDeptName = optional(DoctorDepartment::find($existingDoctor->doctor_departments))->name ?? 'another department';

        return back()
            ->withInput()
            ->with('error', "This doctor is already registered under {$existingDeptName}. Please remove them from that department before adding them here.");
    }

    $resetToken = Str::random(64);

    $doctor = Doctor::create([
        'full_name' => $validatedData['full_name'],
        'email' => $validatedData['email'],
        'doctor_departments' => $departmentId,
        'doctor_unit_id' => $validatedData['doctor_unit_id'],
        'doctor_sub_unit_id' => $validatedData['doctor_sub_unit_id'] ?: null,
        'hospital_id' => $validatedData['hospital_id'],
        'user_id' => Auth::id(),
        'password' => Hash::make(Str::random(40)), // locked placeholder — doctor cannot log in until reset
        'cadre' => $validatedData['cadre'],
        'password_reset_token' => $resetToken,
        'password_reset_expires_at' => now()->addHours(48),
        'password_set' => false,
    ]);

    DoctorNotification::create([
        'doctor_unit_id' => $doctor->doctor_unit_id,
        'doctor_sub_unit_id' => $doctor->doctor_sub_unit_id,
        'department_id' => $doctor->doctor_departments,
        'hospital_id' => $doctor->hospital_id,
        'patient_id' => null,
        'title' => 'Doctor Registered',
        'message' => 'Your account has been registered successfully.',
        'type' => 'doctor_registered',
    ]);

    Mail::to($doctor->email)->send(new \App\Mail\DoctorRegisteredMail($doctor, $resetToken));

    return redirect()->route('add_doctor')->with('success', 'Doctor registered successfully! A password setup link has been sent to their email.');
}
    

//   public function showDoctorList()
// {
//     $doctors = Doctor::with('hospitals')->get();

//     // load lists used by the modal selects
//     $departments = DoctorDepartment::orderBy('name')->get();
//     // dd($departments);
//     $doctorUnits = DoctorUnit::orderBy('name')->get();
//     $doctorSubUnits = DoctorSubUnit::orderBy('name')->get();

//     return view('parklane.doctor_list', compact('doctors', 'departments', 'doctorUnits', 'doctorSubUnits'));
// }

public function showDoctorList()
{
    $user = Auth::user();

    $doctorsQuery = Doctor::with('hospitals');

    if (!$user->is_super_admin) {
        $doctorsQuery->where('doctor_departments', $user->doctor_department_id);
    }

    $doctors = $doctorsQuery->get();

    $departmentsQuery = DoctorDepartment::orderBy('name');
    $departments = $user->is_super_admin
        ? $departmentsQuery->get()
        : $departmentsQuery->where('id', $user->doctor_department_id)->get();

    $doctorUnits = DoctorUnit::orderBy('name')->get();
    $doctorSubUnits = DoctorSubUnit::orderBy('name')->get();

    return view('parklane.doctor_list', compact('doctors', 'departments', 'doctorUnits', 'doctorSubUnits'));
}


//     public function updateDepartment(Request $request)
// {
//     $request->validate([
//         'doctor_id' => 'required',
//         'doctor_department_id' => 'required',
//         'doctor_unit_id' => 'required',
//         'doctor_sub_unit_id' => 'nullable',
//     ]);

//     $doctor = Doctor::findOrFail($request->doctor_id);

//     $doctor->doctor_departments = $request->doctor_department_id;
//     $doctor->doctor_unit_id = $request->doctor_unit_id;
//     $doctor->doctor_sub_unit_id = $request->doctor_sub_unit_id;

//     $doctor->save();

//     return back()->with('success', 'Doctor department and unit updated successfully!');
// }


public function updateDepartment(Request $request)
{
    $request->validate([
        'doctor_id' => 'required',
        'doctor_department_id' => 'required',
        'doctor_unit_id' => 'required',
        'doctor_sub_unit_id' => 'nullable',
    ]);

    $doctor = Doctor::findOrFail($request->doctor_id);
    $user = Auth::user();

    if (!$user->is_super_admin) {
        if ($doctor->doctor_departments != $user->doctor_department_id) {
            abort(403, 'You do not have access to this doctor.');
        }
        if ((int) $request->doctor_department_id !== (int) $user->doctor_department_id) {
            abort(403, 'You cannot move a doctor out of your department.');
        }
    }

    // Capture old values before overwriting, so we know what actually changed
    $oldDepartmentId = $doctor->doctor_departments;
    $oldUnitId = $doctor->doctor_unit_id;
    $oldSubUnitId = $doctor->doctor_sub_unit_id;

    $doctor->doctor_departments = $request->doctor_department_id;
    $doctor->doctor_unit_id = $request->doctor_unit_id;
    $doctor->doctor_sub_unit_id = $request->doctor_sub_unit_id;
    $doctor->save();

    $changed = (int) $oldDepartmentId !== (int) $doctor->doctor_departments
        || (int) $oldUnitId !== (int) $doctor->doctor_unit_id
        || (int) ($oldSubUnitId ?? 0) !== (int) ($doctor->doctor_sub_unit_id ?? 0);

    if ($changed) {
        $oldDeptName = optional(DoctorDepartment::find($oldDepartmentId))->name ?? 'N/A';
        $newDeptName = optional(DoctorDepartment::find($doctor->doctor_departments))->name ?? 'N/A';
        $oldUnitName = optional(DoctorUnit::find($oldUnitId))->name ?? 'N/A';
        $newUnitName = optional(DoctorUnit::find($doctor->doctor_unit_id))->name ?? 'N/A';
        $oldSubUnitName = optional(DoctorSubUnit::find($oldSubUnitId))->name ?? 'None';
        $newSubUnitName = optional(DoctorSubUnit::find($doctor->doctor_sub_unit_id))->name ?? 'None';

        DoctorNotification::create([
            'doctor_unit_id' => $doctor->doctor_unit_id,
            'doctor_sub_unit_id' => $doctor->doctor_sub_unit_id,
            'department_id' => $doctor->doctor_departments,
            'hospital_id' => $doctor->hospital_id,
            'patient_id' => null,
            'title' => 'Department/Unit Changed',
            'message' => "Your assignment was updated to {$newDeptName} / {$newUnitName} / {$newSubUnitName}.",
            'type' => 'doctor_reassigned',
        ]);

        Mail::to($doctor->email)->send(new \App\Mail\DoctorReassignedMaiL(
            $doctor,
            $oldDeptName,
            $newDeptName,
            $oldUnitName,
            $newUnitName,
            $oldSubUnitName,
            $newSubUnitName
        ));
    }

    return back()->with('success', 'Doctor department and unit updated successfully!');
}


//     public function show($id)
// {
//     $doctor = Doctor::findOrFail($id);

//       $activities = DoctorActivity::where('doctor_id', $doctor->id)
//         ->latest()
//         ->paginate(20);

//     return view('parklane.show_doctor', compact('doctor', 'activities'));
// }

// public function show($id)
// {
//     $doctor = Doctor::with([
//         'doctorDepartment',
//         'doctorUnit',
//         'subUnit',
//         'hospitals',
//     ])->findOrFail($id);

//     $activities = DoctorActivity::where('doctor_id', $doctor->id)
//         ->latest()
//         ->paginate(20);

//     $stats = [
//         'patients' => \App\Models\Patient::where('doctor_id', $doctor->id)
//             ->orWhere('doctor_assign', $doctor->id)
//             ->count(),
//         'invites' => \App\Models\PatientUnitInvite::where('doctor_id', $doctor->id)->count(),
//         'discharged' => \App\Models\Patient::where('doctor_id', $doctor->id)
//             ->where('status', 'discharged')
//             ->count(),
//     ];

//     return view('parklane.show_doctor', compact('doctor', 'activities', 'stats'));
// }

//     public function destroy($id)
//     {
//         $doctor = Doctor::findOrFail($id);
//         // dd($doctor);
//         $doctor->delete();

//         return redirect()->route('doctor_list')->with('success', 'Doctor deleted successfully!');
//     }




public function show($id)
{
    $doctor = Doctor::with(['doctorDepartment', 'doctorUnit', 'subUnit', 'hospitals'])->findOrFail($id);

    $user = Auth::user();
    if (!$user->is_super_admin && $doctor->doctor_departments != $user->doctor_department_id) {
        abort(403, 'You do not have access to this doctor.');
    }

    $activities = DoctorActivity::where('doctor_id', $doctor->id)->latest()->paginate(20);

    $stats = [
        'patients' => \App\Models\Patient::where('doctor_id', $doctor->id)
            ->orWhere('doctor_assign', $doctor->id)
            ->count(),
        'invites' => \App\Models\PatientUnitInvite::where('doctor_id', $doctor->id)->count(),
        'discharged' => \App\Models\Patient::where('doctor_id', $doctor->id)
            ->where('status', 'discharged')
            ->count(),
    ];

    return view('parklane.show_doctor', compact('doctor', 'activities', 'stats'));
}

public function destroy($id)
{
    $doctor = Doctor::findOrFail($id);

    $user = Auth::user();
    if (!$user->is_super_admin && $doctor->doctor_departments != $user->doctor_department_id) {
        abort(403, 'You do not have access to this doctor.');
    }

    $doctor->delete();

    return redirect()->route('doctor_list')->with('success', 'Doctor deleted successfully!');
}






    public function createDoctorShift($doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);
        return view('parklane.add_doctor_shift', compact('doctor'));
    }

    public function storeDoctorShift(Request $request, $doctorId)
    {
        $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
    
            'sun_start' => 'nullable|string',
            'sun_end' => 'nullable|string',
            'mon_start' => 'nullable|string',
            'mon_end' => 'nullable|string',
            'tue_start' => 'nullable|string',
            'tue_end' => 'nullable|string',
            'wed_start' => 'nullable|string',
            'wed_end' => 'nullable|string',
            'thu_start' => 'nullable|string',
            'thu_end' => 'nullable|string',
            'fri_start' => 'nullable|string',
            'fri_end' => 'nullable|string',
            'sat_start' => 'nullable|string',
            'sat_end' => 'nullable|string',
        ]);
    
        ShiftSchedule::create([
            'doctor_id' => $doctorId,
            'hospital_id' => $request->hospital_id,
    
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
    
        return redirect()->route('doctors.shifts', $doctorId)->with('success', 'Shift added successfully!');
    }

    public function doctorShift()
    {
        $shifts = \App\Models\ShiftSchedule::with(['doctor', 'doctor.doctorDepartment', 'hospital'])->get();
        // dd($shifts);
        return view('parklane.doctor_shift_list', compact('shifts'));
    }

    public function destroyShift($id)
    {
        $shift = ShiftSchedule::findOrFail($id);
        $shift->delete();
    
        return redirect()->route('doctors.shifts')->with('success', 'Shift deleted successfully!');
    }

    public function editDoctorShift($id)
    {
        $shift = ShiftSchedule::with('doctor')->findOrFail($id);
        // dd( $shift);
        return view('parklane.update_doctor_shift', compact('shift'));
    }

    public function updateDoctorShift(Request $request, $id)
    {
       // dd($request->all());
        $request->validate([
           
            'doctor_id' => 'required|exists:doctors,id',
            'hospital_id' => 'required|exists:hospitals,id',
    
            // Optionally validate shift times
            'sun_start' => 'nullable|string',
            'sun_end' => 'nullable|string',
            'mon_start' => 'nullable|string',
            'mon_end' => 'nullable|string',
            'tue_start' => 'nullable|string',
            'tue_end' => 'nullable|string',
            'wed_start' => 'nullable|string',
            'wed_end' => 'nullable|string',
            'thu_start' => 'nullable|string',
            'thu_end' => 'nullable|string',
            'fri_start' => 'nullable|string',
            'fri_end' => 'nullable|string',
            'sat_start' => 'nullable|string',
            'sat_end' => 'nullable|string',
        ]);
    
        $shift = ShiftSchedule::findOrFail($id);
        $shift->update($request->all());
    
        return redirect()->route('doctorShift')->with('success', 'Shift updated successfully!');
    }


    public function messageUnit(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:doctor_departments,id',
            'doctor_unit_id' => 'required|exists:doctor_units,id',
            'doctor_sub_unit_id' => 'nullable|exists:doctor_sub_units,id',
            'message' => 'required|string',
        ]);

        // Target doctors: sub‑unit if provided, else unit
        if (!empty($request->doctor_sub_unit_id)) {
            $targetDoctors = Doctor::where('doctor_sub_unit_id', $request->doctor_sub_unit_id)->get();
        } else {
            $targetDoctors = Doctor::where('doctor_unit_id', $request->doctor_unit_id)->get();
        }

        foreach ($targetDoctors as $doc) {
            // Notification
            DoctorNotification::create([
                'department_id' => $doc->doctor_departments,
                'hospital_id' => $doc->hospital_id,
                'doctor_unit_id' => $doc->doctor_unit_id,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id,
                'patient_id' => null,
                'title' => 'Admin Message',
                'message' => $request->message,
                'type' => 'admin_message',
            ]);

            \Log::info('Creating notification', [
                'department_id' => $doc->doctor_departments ?? null,
                'hospital_id' => $doc->hospital_id ?? null,
                'doctor_unit_id' => $doc->doctor_unit_id ?? null,
                'doctor_sub_unit_id' => $doc->doctor_sub_unit_id ?? null,
                'patient_id' => $patient->id ?? null,
                'title' => 'Test',
                'message' => 'Test',
                'type' => 'test',
            ]);




            // Email
            Mail::to($doc->email)->send(new \App\Mail\AdminMessageMail($doc, $request->message));
        }

        return back()->with('success', 'Message sent to unit/sub‑unit successfully.');
    }
    

    
    
    
    


}
