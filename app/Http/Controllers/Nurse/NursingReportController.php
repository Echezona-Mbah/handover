<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\NursingReport;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NursingReportController extends Controller
{
    public function index()
    {
        $nurse = Auth::guard('nurse')->user();

        $reports = NursingReport::with(['patient', 'ward'])
            ->where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->latest()
            ->paginate(15);

        return view('parklane.nurses.digital_reports.index', compact('reports'));
    }

    public function create()
    {
        $nurse = Auth::guard('nurse')->user();
        $patients = Patient::where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->orderBy('full_name')
            ->get();

        return view('parklane.nurses.digital_reports.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $nurse = Auth::guard('nurse')->user();

        $validated = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'report_type' => 'required|string|max:100',
            'report_date' => 'required|date',
            'shift' => 'nullable|string|max:50',
            'summary' => 'required|string',
            'incidents' => 'nullable|string',
            'referrals' => 'nullable|string',
            'administrative_notes' => 'nullable|string',
        ]);

        $validated['hospital_id'] = $nurse->hospital_id;
        $validated['ward_id'] = $nurse->ward_id;
        $validated['nurse_id'] = $nurse->id;
        $validated['status'] = 'submitted';
        $validated['submitted_at'] = now();

        $report = NursingReport::create($validated);

        return redirect()->route('nurse.reports.show', $report)
            ->with('success', 'Nursing report submitted successfully.');
    }

    public function show(NursingReport $report)
    {
        $nurse = Auth::guard('nurse')->user();

        if (! $this->belongsToNurseAssignment($report, $nurse)) {
            return redirect()->route('nurse.reports.index')
                ->with('error', 'You can only view reports for your assigned ward or clinic.');
        }

        $report->load(['patient', 'ward', 'nurse', 'reviews.reviewer']);

        return view('parklane.nurses.digital_reports.show', compact('report'));
    }

    private function belongsToNurseAssignment(NursingReport $report, $nurse): bool
    {
        return (string) $report->hospital_id === (string) $nurse->hospital_id
            && (string) $report->ward_id === (string) $nurse->ward_id;
    }
}
