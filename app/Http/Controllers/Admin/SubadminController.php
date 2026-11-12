<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminManagerCreatedMail;
use App\Models\DoctorDepartment;
use App\Models\Hospital;
use App\Models\Nurses;
use App\Models\NursingQuery;
use App\Models\NursingReport;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\NursingAdministrationAdded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use App\Services\NursingHierarchy;

class SubadminController extends Controller
{
    public function create()
    {
        $departments = DoctorDepartment::all();
        $doctorUnits = \App\Models\DoctorUnit::with('subUnits')->whereNull('parent_id')->get();
        $managers = User::where('is_super_admin', false)->with('doctorDepartment', 'hospitals')->get();
        $hospitals = Hospital::all();
        $wards = \App\Models\Ward::with(['unitHead', 'nurseDepartment'])->where('hospital_id', auth()->user()->hospital_id)->orderBy('name')->get();
        $nursingUnits = \App\Models\NurseDepartment::whereHas('wards', fn ($query) => $query
            ->where('hospital_id', auth()->user()->hospital_id))
            ->whereDoesntHave('wards', fn ($query) => $query
                ->where('hospital_id', auth()->user()->hospital_id)
                ->whereNotNull('unit_head_id'))
            ->orderBy('name')
            ->get();
        $nurses = Nurses::with('ward')->where('hospital_id', auth()->user()->hospital_id)->orderBy('full_name')->get();
        $wardSupervisors = \App\Models\Ward::with(['nurses' => fn ($query) => $query->where('nursing_hierarchy_role', 'ward_supervision')])
            ->where('hospital_id', auth()->user()->hospital_id)
            ->orderBy('name')
            ->get();

        return view('parklane.add_admin', compact('departments', 'doctorUnits', 'managers', 'hospitals', 'wards', 'nursingUnits', 'nurses', 'wardSupervisors'));
    }

    public function store(Request $request)
    {
        if ($request->input('admin_area') === 'nursing_ward_supervisor') {
            $hospitalId = (int) $request->user()->hospital_id;
            abort_unless($hospitalId, 403, 'Your administrator account is not assigned to a hospital.');

            $data = $request->validate([
                'supervisor_ward_id' => [
                    'required',
                    'integer',
                    Rule::exists('wards', 'id')->where(fn ($query) => $query->where('hospital_id', $hospitalId)),
                ],
                'supervisor_nurse_id' => [
                    'required',
                    'integer',
                    Rule::exists('nurses', 'id')->where(fn ($query) => $query
                        ->where('hospital_id', $hospitalId)
                        ->where('ward_id', $request->input('supervisor_ward_id'))),
                ],
            ]);

            DB::transaction(function () use ($data, $hospitalId) {
                Nurses::where('hospital_id', $hospitalId)
                    ->where('ward_id', $data['supervisor_ward_id'])
                    ->where('nursing_hierarchy_role', 'ward_supervision')
                    ->where('id', '!=', $data['supervisor_nurse_id'])
                    ->update(['nursing_hierarchy_role' => 'ward_nurse']);

                Nurses::where('hospital_id', $hospitalId)
                    ->where('ward_id', $data['supervisor_ward_id'])
                    ->whereKey($data['supervisor_nurse_id'])
                    ->update(['nursing_hierarchy_role' => 'ward_supervision']);
            });

            return redirect()->route('add_admin_user')->with('success', 'Ward supervisor assigned successfully.');
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'admin_area' => 'required|in:doctor_department,nursing_691,nursing_management,nursing_hns,nursing_162,nursing_unit_head,nursing_ward_supervisor',
            'doctor_department_id' => 'nullable|required_if:admin_area,doctor_department|exists:doctor_departments,id',
            'hospital_id' => 'required|exists:hospitals,id',
            'unit_head_department_id' => 'nullable|required_if:admin_area,nursing_unit_head|integer|exists:nurse_departments,id',
        ]);

        $plainPassword = $validated['password'];
        $adminArea = $validated['admin_area'];
        $unitId = $validated['unit_head_department_id'] ?? null;
        $validated['nursing_hierarchy_role'] = match ($adminArea) {
            'nursing_hns' => 'hns',
            'nursing_162' => 'control_162',
            'nursing_unit_head' => 'unit_head',
            'nursing_691', 'nursing_management' => 'control_162',
            default => null,
        };
        $validated['nursing_admin_role'] = in_array($adminArea, ['nursing_691', 'nursing_management'], true)
            ? $adminArea
            : null;
        unset($validated['admin_area'], $validated['unit_head_department_id']);
        $validated['password'] = Hash::make($validated['password']);

        $manager = DB::transaction(function () use ($validated, $unitId) {
            $manager = User::create($validated);

            if ($manager->nursing_hierarchy_role === 'unit_head') {
                $unitWards = \App\Models\Ward::where('hospital_id', $manager->hospital_id)
                    ->where('nurse_department_id', $unitId)
                    ->lockForUpdate()
                    ->get();
                $wardCount = $unitWards->count();
                $availableWardCount = $unitWards->filter(fn ($ward) => $ward->unit_head_id === null)->count();
                abort_unless($wardCount > 0 && $availableWardCount === $wardCount, 422, 'The selected nursing unit has no wards available for assignment.');

                \App\Models\Ward::where('hospital_id', $manager->hospital_id)
                    ->where('nurse_department_id', $unitId)
                    ->update(['unit_head_id' => $manager->id]);
            }

            return $manager;
        });

        $department = DoctorDepartment::find($manager->doctor_department_id);

        Mail::to($manager->email)->send(
            new AdminManagerCreatedMail($manager, $plainPassword, $department->name ?? $this->nursingRoleLabel($adminArea))
        );

        if ($manager->nursing_admin_role || $manager->nursing_hierarchy_role) {
            $administrationName = $this->nursingRoleLabel($adminArea).' Administration';

            Nurses::where('hospital_id', $manager->hospital_id)
                ->each(fn (Nurses $nurse) => $nurse->notify(new NursingAdministrationAdded($administrationName, auth()->user()->name)));
        }

        return redirect()->route('add_admin_user')->with('success', 'Admin account added.');
    }

    public function show(User $user)
    {
        $actor = auth()->user();
        abort_unless((int) $actor->hospital_id === (int) $user->hospital_id, 404);

        $role = NursingHierarchy::role($user);
        $roleLabel = match ($role) {
            'hns' => 'Head of Nursing Services',
            'control_162' => '162 Control Unit',
            'unit_head' => 'Nursing Unit Head',
            default => $user->nursing_admin_role
                ? $this->nursingRoleLabel($user->nursing_admin_role)
                : ($user->doctorDepartment?->name ?? 'Administrator'),
        };
        $managedWards = collect();
        $recentReports = collect();
        $recentQueries = collect();

        if ($role === 'unit_head') {
            $wardIds = NursingHierarchy::wardsFor($user);
            $managedWards = \App\Models\Ward::whereIn('id', $wardIds)
                ->withCount([
                    'nurses',
                    'patients',
                    'nursingReports',
                ])
                ->with(['nurses' => fn ($query) => $query->orderBy('full_name')])
                ->orderBy('name')
                ->get();
            $recentReports = NursingReport::with(['nurse', 'ward', 'patient'])
                ->where('hospital_id', $user->hospital_id)
                ->whereIn('ward_id', $wardIds)
                ->latest('submitted_at')
                ->limit(30)
                ->get();
            $recentQueries = NursingQuery::with(['ward', 'report'])
                ->where('hospital_id', $user->hospital_id)
                ->whereIn('ward_id', $wardIds)
                ->latest()
                ->limit(30)
                ->get();
        }

        return view('parklane.admin_manager_show', compact(
            'user',
            'role',
            'roleLabel',
            'managedWards',
            'recentReports',
            'recentQueries',
        ));
    }

    public function resendPasswordSetupLink(User $user)
    {
        $actor = auth()->user();
        abort_unless($actor->hospital_id, 403, 'Your administrator account is not assigned to a hospital.');
        abort_unless(
            (int) $actor->hospital_id === (int) $user->hospital_id && ! $user->isSuperAdmin(),
            404
        );

        $status = Password::broker('users')->sendResetLink(['email' => $user->email]);
        if ($status === Password::RESET_THROTTLED) {
            return back()->with('error', 'A password setup link was sent recently. Please wait before requesting another.');
        }
        if ($status !== Password::RESET_LINK_SENT) {
            return back()->with('error', 'The password setup email could not be sent. Please try again.');
        }

        return back()->with('success', 'A new password setup link was emailed to '.$user->email.'.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('add_admin_user')->with('success', 'Manager removed.');
    }

    private function nursingRoleLabel(?string $role): string
    {
        return match ($role) {
            'nursing_691' => '691 Nurse',
            'nursing_management' => 'Nurse Management',
            'nursing_hns' => 'Head of Nursing Services',
            'nursing_162' => '162 Control Unit',
            'nursing_unit_head' => 'Nursing Unit Head',
            default => 'Department Manager',
        };
    }
}
