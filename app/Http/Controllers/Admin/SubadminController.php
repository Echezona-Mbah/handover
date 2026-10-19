<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminManagerCreatedMail;
use App\Models\DoctorDepartment;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SubadminController extends Controller
{
    public function create()
    {
        $departments = DoctorDepartment::all();
        $doctorUnits = \App\Models\DoctorUnit::with('subUnits')->whereNull('parent_id')->get();
        $managers = User::where('is_super_admin', false)->with('doctorDepartment', 'hospitals')->get();
        $hospitals = Hospital::all();

        return view('parklane.add_admin', compact('departments', 'doctorUnits', 'managers', 'hospitals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'doctor_department_id' => 'required|exists:doctor_departments,id',
            'hospital_id' => 'required|exists:hospitals,id',
        ]);

        $plainPassword = $validated['password'];
        $validated['password'] = Hash::make($validated['password']);

        $manager = User::create($validated);

        $department = DoctorDepartment::find($manager->doctor_department_id);

        Mail::to($manager->email)->send(
            new AdminManagerCreatedMail($manager, $plainPassword, $department->name ?? 'N/A')
        );

        return redirect()->route('add_admin_user')->with('success', 'Department manager added.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('add_admin_user')->with('success', 'Manager removed.');
    }
}