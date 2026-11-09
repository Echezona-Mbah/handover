<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorDepartment;
use App\Models\DoctorUnit;
use App\Models\DoctorSubUnit;
use App\Models\NurseDepartment;
use App\Models\Ward;
use App\Models\User;
use App\Services\NursingHierarchy;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        $actor = auth()->user();
        $role = NursingHierarchy::role($actor);
        abort_if($role !== null && ! in_array($role, ['hns', 'control_162', 'unit_head'], true), 403);

        if ($role === 'unit_head') {
            $wards = Ward::whereIn('id', NursingHierarchy::wardsFor($actor))->orderBy('name')->get();
            $nursingUnits = NurseDepartment::orderBy('name')->get();

            return view('parklane.nursing_admin.wards', compact('wards', 'nursingUnits'));
        }

        $departments = DoctorDepartment::withCount(['units', 'doctors'])->get();
        $units       = DoctorUnit::with('subUnits')->whereNull('parent_id')->get();
        $wards       = Ward::with('nurseDepartment')->where('hospital_id', $actor->hospital_id)->orderBy('category')->orderBy('name')->get();
        $unitHeads = User::where('hospital_id', $actor->hospital_id)
            ->whereIn('nursing_hierarchy_role', ['unit_head', 'nursing_unit_head'])
            ->orderBy('name')
            ->get();
        $nursingUnits = NurseDepartment::orderBy('name')->get();

        return view('parklane.department_manager', compact('departments', 'units', 'wards', 'unitHeads', 'nursingUnits'));
    }

    // ── Departments ───────────────────────────────────────────────────────
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:doctor_departments,name',
            'description' => 'nullable|string|max:500',
        ]);

        DoctorDepartment::create([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return back()->with('success', "Department '{$request->name}' created successfully.");
    }

    public function destroyDepartment($id)
    {
        $dept = DoctorDepartment::findOrFail($id);
        $dept->delete();
        return back()->with('success', 'Department deleted.');
    }

    // ── Units ─────────────────────────────────────────────────────────────
    public function storeUnit(Request $request)
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'doctor_department_id' => 'required|exists:doctor_departments,id',
        ]);

        DoctorUnit::create([
            'name'                 => $request->name,
            'doctor_department_id' => $request->doctor_department_id,
            'parent_id'            => null,
        ]);

        return back()->with('success', "Unit '{$request->name}' created successfully.");
    }

    public function destroyUnit($id)
    {
        $unit = DoctorUnit::findOrFail($id);
        $unit->delete();
        return back()->with('success', 'Unit deleted.');
    }

    // ── Sub Units ─────────────────────────────────────────────────────────
    public function storeSubUnit(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'doctor_unit_id' => 'required|exists:doctor_units,id',
        ]);
        DoctorSubUnit::create([
            'name'           => $request->name,
            'doctor_unit_id' => $request->doctor_unit_id,
        ]);

        return back()->with('success', "Sub-unit '{$request->name}' created successfully.");
    }

    public function destroySubUnit($id)
    {
        $sub = DoctorSubUnit::findOrFail($id);
        $sub->delete();
        return back()->with('success', 'Sub-unit deleted.');
    }




    public function updateDepartment(Request $request, $id)
{
    $dept = DoctorDepartment::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255|unique:doctor_departments,name,' . $dept->id,
        'description' => 'nullable|string|max:500',
    ]);

    $dept->update($request->only('name', 'description'));

    return back()->with('success', "Department '{$dept->name}' updated successfully.");
}

public function updateUnit(Request $request, $id)
{
    $unit = DoctorUnit::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'doctor_department_id' => 'required|exists:doctor_departments,id',
    ]);

    $unit->update([
        'name' => $request->name,
        'doctor_department_id' => $request->doctor_department_id,
    ]);

    return back()->with('success', "Unit '{$unit->name}' updated successfully.");
}

public function updateSubUnit(Request $request, $id)
{
    $sub = DoctorSubUnit::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'doctor_unit_id' => 'required|exists:doctor_units,id',
    ]);

    $sub->update([
        'name' => $request->name,
        'doctor_unit_id' => $request->doctor_unit_id,
    ]);

    return back()->with('success', "Sub-unit '{$sub->name}' updated successfully.");
}















    // ── Wards ─────────────────────────────────────────────────────────────
    public function storeWard(Request $request)
    {
        $actor = $this->authorizeWardManagement();
        $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('wards', 'name')->where('hospital_id', $actor->hospital_id)],
            'description' => 'nullable|string|max:500',
            'category'    => 'required|in:Ward,Clinic,Theatre,Unit,Other',
            'linen_color' => 'nullable|string|max:100',
            'nurse_department_id' => 'required|exists:nurse_departments,id',
            'unit_head_id' => $this->unitHeadRule($actor),
        ]);

        Ward::create([
            'name'        => $request->name,
            'description' => $request->description,
            'category'    => $request->category,
            'linen_color' => $request->linen_color,
            'nurse_department_id' => $request->nurse_department_id,
            'hospital_id' => $actor->hospital_id,
            'unit_head_id' => NursingHierarchy::role($actor) === 'unit_head'
                ? $actor->id
                : $request->input('unit_head_id'),
        ]);

        return back()->with('success', "Ward '{$request->name}' created successfully.");
    }

    public function updateWard(Request $request, $id)
    {
        $actor = $this->authorizeWardManagement();
        $ward = $this->managedWard($actor, $id);

        $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('wards', 'name')->where('hospital_id', $actor->hospital_id)->ignore($ward->id)],
            'description' => 'nullable|string|max:500',
            'category'    => 'required|in:Ward,Clinic,Theatre,Unit,Other',
            'linen_color' => 'nullable|string|max:100',
            'nurse_department_id' => 'required|exists:nurse_departments,id',
            'unit_head_id' => $this->unitHeadRule($actor),
        ]);

        $ward->update([
            'name'        => $request->name,
            'description' => $request->description,
            'category'    => $request->category,
            'linen_color' => $request->linen_color,
            'nurse_department_id' => $request->nurse_department_id,
            'unit_head_id' => NursingHierarchy::role($actor) === 'unit_head'
                ? $actor->id
                : $request->input('unit_head_id'),
        ]);

        return back()->with('success', "Ward '{$ward->name}' updated successfully.");
    }

    public function destroyWard($id)
    {
        $actor = $this->authorizeWardManagement();
        abort_if(NursingHierarchy::role($actor) === 'unit_head', 403);
        $ward = $this->managedWard($actor, $id);
        $ward->delete();
        return back()->with('success', 'Ward deleted.');
    }

    private function authorizeWardManagement(): User
    {
        $actor = auth()->user();
        $role = NursingHierarchy::role($actor);
        abort_if($role !== null && ! in_array($role, ['hns', 'control_162', 'unit_head'], true), 403);

        return $actor;
    }

    private function managedWard(User $actor, int|string $wardId): Ward
    {
        $wards = Ward::where('hospital_id', $actor->hospital_id);
        if (NursingHierarchy::role($actor) === 'unit_head') {
            $wards->where('unit_head_id', $actor->id);
        }

        return $wards->findOrFail($wardId);
    }

    private function unitHeadRule(User $actor): array
    {
        if (NursingHierarchy::role($actor) === 'unit_head') {
            return ['prohibited'];
        }

        return [
            'nullable',
            Rule::exists('users', 'id')->where(fn ($query) => $query
                ->where('hospital_id', $actor->hospital_id)
                ->whereIn('nursing_hierarchy_role', ['unit_head', 'nursing_unit_head'])),
        ];
    }
}
