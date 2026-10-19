<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorDepartment;
use App\Models\DoctorUnit;
use App\Models\DoctorSubUnit;
use App\Models\Ward;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = DoctorDepartment::withCount(['units', 'doctors'])->get();
        $units       = DoctorUnit::with('subUnits')->whereNull('parent_id')->get();
        $wards       = Ward::where('hospital_id', auth()->user()->hospital_id)->orderBy('category')->orderBy('name')->get();

        return view('parklane.department_manager', compact('departments', 'units', 'wards'));
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
        $request->validate([
            'name'        => 'required|string|max:255|unique:wards,name',
            'description' => 'nullable|string|max:500',
            'category'    => 'required|in:Ward,Clinic,Theatre,Unit,Other',
            'linen_color' => 'nullable|string|max:100',
        ]);

        Ward::create([
            'name'        => $request->name,
            'description' => $request->description,
            'category'    => $request->category,
            'linen_color' => $request->linen_color,
            'hospital_id' => auth()->user()->hospital_id,
        ]);

        return back()->with('success', "Ward '{$request->name}' created successfully.");
    }

    public function updateWard(Request $request, $id)
    {
        $ward = Ward::where('hospital_id', auth()->user()->hospital_id)->findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:wards,name,' . $ward->id,
            'description' => 'nullable|string|max:500',
            'category'    => 'required|in:Ward,Clinic,Theatre,Unit,Other',
            'linen_color' => 'nullable|string|max:100',
        ]);

        $ward->update([
            'name'        => $request->name,
            'description' => $request->description,
            'category'    => $request->category,
            'linen_color' => $request->linen_color,
        ]);

        return back()->with('success', "Ward '{$ward->name}' updated successfully.");
    }

    public function destroyWard($id)
    {
        $ward = Ward::where('hospital_id', auth()->user()->hospital_id)->findOrFail($id);
        $ward->delete();
        return back()->with('success', 'Ward deleted.');
    }
}
