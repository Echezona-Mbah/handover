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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class NuresController extends Controller
{
    public function createNurse()
    {
        $wards = Ward::where('hospital_id', auth()->user()->hospital_id)
            ->whereNotNull('nurse_department_id')
            ->orderBy('category')
            ->orderBy('name')
            ->get();
        $departments = NurseDepartment::whereIn('id', $wards->pluck('nurse_department_id')->unique())
            ->orderBy('name')
            ->get();
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
            'phone_number' => 'nullable|string|max:30',
            'license_number' => 'nullable|string|max:100',
            'qualification' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Inactive',
            'nurse_department_id' => ['required', 'integer', Rule::exists('nurse_departments', 'id')],
            'ward_id' => [
                'required',
                Rule::exists('wards', 'id')->where(fn ($query) => $query
                    ->where('hospital_id', $hospitalId)
                    ->where('nurse_department_id', $request->input('nurse_department_id'))),
            ],
            'nursing_role_id' => [
                'nullable',
                Rule::exists('nursing_roles', 'id')->whereIn('name', NursingRole::CADRES),
            ],
            'nursing_hierarchy_role' => ['required', Rule::in(['ward_nurse', 'ward_supervision'])],

        ]);

        $nurse = Nurses::create([
            ...$validatedData,
            'hospital_id' => $hospitalId,
            'password' => Hash::make(Str::random(64)),
        ]);

        $status = Password::broker('nurses')->sendResetLink(['email' => $nurse->email]);
        if ($status !== Password::RESET_LINK_SENT) {
            return redirect()->route('add_nurse')
                ->with('error', 'Nurse registered, but the password setup email could not be sent. Ask the nurse to use Forgot password on the nurse login page.');
        }

        return redirect()->route('add_nurse')
            ->with('success', 'Nurse registered. Login details and a secure password setup link were emailed to the nurse.');
    }

    public function showNurseList()
    {
        $nurses = Nurses::with(['hospitals', 'ward', 'NursingRole', 'NurseDepartment'])
            ->where('hospital_id', Auth::user()->hospital_id)
            ->orderBy('full_name')
            ->get();

        return view('parklane.nurses_list', compact('nurses'));
    }

    public function updateHierarchyRole(Request $request, int $id)
    {
        $validated = $request->validate([
            'nursing_hierarchy_role' => ['required', Rule::in(['ward_nurse', 'ward_supervision'])],
        ]);

        $nurse = Nurses::where('hospital_id', Auth::user()->hospital_id)->findOrFail($id);
        DB::transaction(function () use ($nurse, $validated) {
            if ($validated['nursing_hierarchy_role'] === 'ward_supervision') {
                Nurses::where('hospital_id', $nurse->hospital_id)
                    ->where('ward_id', $nurse->ward_id)
                    ->where('nursing_hierarchy_role', 'ward_supervision')
                    ->where('id', '!=', $nurse->id)
                    ->update(['nursing_hierarchy_role' => 'ward_nurse']);
            }

            $nurse->update($validated);
        });

        return redirect()->route('nurse_list')->with('success', 'Nursing responsibility updated.');
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
