<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NursingReport;
use App\Models\Patient;
use App\Models\Ward;
use App\Services\NursingHierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NursingDirectoryController extends Controller
{
    public function patients(Request $request)
    {
        $actor = Auth::user();
        abort_unless(NursingHierarchy::canManageNursingDirectory($actor), 403);

        $wards = Ward::whereIn('id', NursingHierarchy::wardsFor($actor))
            ->orderBy('name')
            ->get();
        $patients = NursingHierarchy::visiblePatients($actor)
            ->with('ward')
            ->when($request->filled('ward_id'), fn ($query) => $query->where('ward_id', $request->integer('ward_id')))
            ->orderBy('full_name')
            ->paginate(20)
            ->withQueryString();

        return view('parklane.nursing_admin.patients', compact('patients', 'wards'));
    }

    public function patient(Patient $patient)
    {
        $actor = Auth::user();
        abort_unless(
            NursingHierarchy::canManageNursingDirectory($actor)
                && (int) $patient->hospital_id === (int) $actor->hospital_id
                && $patient->ward_id
                && in_array((int) $patient->ward_id, NursingHierarchy::wardsFor($actor), true),
            403
        );

        $patient->load(['ward', 'doctorById', 'doctorUnit', 'doctorSubUnit']);
        $reports = NursingReport::with(['nurse', 'reviews.reviewer'])
            ->where('hospital_id', $actor->hospital_id)
            ->where('ward_id', $patient->ward_id)
            ->where('patient_id', $patient->id)
            ->where('status', '!=', 'draft')
            ->latest('report_date')
            ->latest('id')
            ->get();
        $handoverNotes = \App\Models\NurseHandover::where('hospital_id', $actor->hospital_id)
            ->where('ward_id', $patient->ward_id)
            ->where('patient_id', $patient->id)
            ->latest()
            ->get();
        $medications = \App\Models\Medication::where('patient_id', $patient->id)->latest()->get();
        $nursingProcessRecords = $patient->nursingProcessRecords()->with('nurse')->get();

        return view('parklane.nursing_admin.patient_reports', compact('patient', 'reports', 'handoverNotes', 'medications', 'nursingProcessRecords'));
    }

    public function nurses(Request $request)
    {
        $actor = Auth::user();
        abort_unless(NursingHierarchy::canManageNursingDirectory($actor), 403);

        $wards = Ward::whereIn('id', NursingHierarchy::wardsFor($actor))
            ->orderBy('name')
            ->get();
        $nurses = NursingHierarchy::visibleNurses($actor)
            ->with('ward')
            ->when($request->filled('ward_id'), fn ($query) => $query->where('ward_id', $request->integer('ward_id')))
            ->orderBy('ward_id')
            ->orderBy('full_name')
            ->paginate(25)
            ->withQueryString();

        return view('parklane.nursing_admin.nurses', compact('nurses', 'wards'));
    }
}
