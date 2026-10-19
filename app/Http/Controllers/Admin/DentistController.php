<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DentistDepartment;
use App\Models\DentistRole as ModelsDentistRole;
use App\Models\DentistryRole;
use App\Models\Dentists;
use App\Models\DentistUnit;
use App\Models\ShiftSchedule;
use Database\Seeders\DentistROle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DentistController extends Controller
{
    public function create()
    {
        $dentistDepartments = DentistDepartment::all();
        $DentistROles = DentistryRole::all();
        $Dentistunits = DentistUnit::all();
        return view('parklane.add_dentist',compact('dentistDepartments','DentistROles','Dentistunits'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'dentist_role_id' => 'required',
            'dentist_department_id' => 'required',
            'dentist_unit_id' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'hospital_id' => 'required|exists:hospitals,id',
        ]);
        // dd(Auth::id());


        //print_r($request->hospital_id);die();

        Dentists::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'hospital_id' => $request->hospital_id,
            'dentist_role_id' => $request->dentist_role_id,
            'dentist_unit_id' => $request->dentist_unit_id,
            'dentist_department_id' => $request->dentist_department_id,
            'user_id' => Auth::id(),
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('add_dentist')->with('success', 'Dentists registered successfully!');
    }

    public function showDentistList()
    {
        $dentists = Dentists::with('hospitals')->get(); 
        // dd($doctors);
        return view('parklane.dentist_list',compact('dentists'));
    }

    public function show($id)
    {
        $dentist = Dentists::findOrFail($id);
        return view('parklane.show_dentist', compact('dentist'));
    }

    public function destroy($id)
    {
        $dentist = Dentists::findOrFail($id);
        // dd($doctor);
        $dentist->delete();

        return redirect()->route('dentist_list')->with('success', 'Dentist deleted successfully!');
    }

    public function createDentistShift($dentistId)
    {
        $dentist = Dentists::findOrFail($dentistId);
        return view('parklane.add_dentist_shift', compact('dentist'));
    }

    public function storeDentistShift(Request $request, $dentistId)
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
            'dentist_id' => $dentistId,
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
    
        return redirect()->route('dentists.shifts', $dentistId)->with('success', 'Shift added successfully!');
    }


    public function dentistShift()
    {
        $shifts = \App\Models\ShiftSchedule::with(['dentist', 'hospital'])
        ->whereNotNull('dentist_id')
        ->get();
        // dd($shifts);
        return view('parklane.dentist_shift_list', compact('shifts'));
    }

    public function destroydentistShift($id)
    {
        $shift = ShiftSchedule::findOrFail($id);
        $shift->delete();
    
        return redirect()->route('dentistShift')->with('success', 'Shift deleted successfully!');
    }

    public function editDentistShift($id)
    {
        $shift = ShiftSchedule::with('dentist')->findOrFail($id);
        // dd( $shift);
        return view('parklane.update_dentist_shift', compact('shift'));
    }

    public function updateDentistShift(Request $request, $id)
    {
       // dd($request->all());
        $request->validate([
           
            'dentist_id' => 'required',
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
    
        return redirect()->route('dentistShift')->with('success', 'Shift updated successfully!');
    }
    
    
    
    

}
