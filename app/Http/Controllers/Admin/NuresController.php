<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NurseDepartment;
use App\Models\Nurses;
use App\Models\NursingRole;
use App\Models\Patient;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class NuresController extends Controller
{
    public function createNurse()
    {
        $wards = Ward::where('hospital_id', auth()->user()->hospital_id)
            ->orderBy('category')
            ->orderBy('name')
            ->get();
        $departments = NurseDepartment::all();
        $nursingRoles = NursingRole::whereIn('name', NursingRole::CADRES)
            ->orderBy('id')
            ->get();

        return view('parklane.add_nurse', compact('wards', 'departments', 'nursingRoles'));
    }

    public function storeNurse(Request $request)
    {

        $hospitalId = Auth::user()->hospital_id;

        abort_unless($hospitalId, 422, 'Your administrator account is not assigned to a hospital.');

        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:nurses,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:30',
            'license_number' => 'nullable|string|max:100',
            'qualification' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
            'nurse_department_id' => 'nullable|exists:nurse_departments,id',
            'ward_id' => [
                'required',
                Rule::exists('wards', 'id')->where(fn ($query) => $query->where('hospital_id', $hospitalId)),
            ],
            'nursing_role_id' => [
                'nullable',
                Rule::exists('nursing_roles', 'id')->whereIn('name', NursingRole::CADRES),
            ],

        ]);

        Nurses::create([
            ...$validatedData,
            'hospital_id' => $hospitalId,
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->route('add_nurse')->with('success', 'Nurse registered successfully!');
    }

    public function showNurseList()
    {
        $nurses = Nurses::with(['hospitals', 'ward', 'NursingRole', 'NurseDepartment'])
            ->where('hospital_id', Auth::user()->hospital_id)
            ->orderBy('full_name')
            ->get();

        return view('parklane.nurses_list', compact('nurses'));
    }

    public function showNures($id)
    {
        $nurse = Nurses::where('hospital_id', Auth::user()->hospital_id)->findOrFail($id);
        $totalPatients = Patient::where('ward_id', $nurse->ward_id)
            ->where('hospital_id', $nurse->hospital_id)
            ->count();

        return view('parklane.show_nurse', compact('nurse', 'totalPatients'));
    }

    public function destroyNurse($id)
    {
        $nurse = Nurses::where('hospital_id', Auth::user()->hospital_id)->findOrFail($id);
        $nurse->delete();

        return redirect()->route('nurse_list')->with('success', 'Nurse deleted successfully!');
    }
}
