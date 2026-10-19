<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Nurses;
use App\Models\NursingRoster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NursingRosterController extends Controller
{
    public function index()
    {
        $nurse = Auth::guard('nurse')->user();

        $rosters = NursingRoster::with('ward')
            ->where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->latest()
            ->paginate(15);

        return view('parklane.nurses.digital_rosters.index', compact('rosters'));
    }

    public function create()
    {
        $nurse = Auth::guard('nurse')->user();
        $nurses = Nurses::where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->where('status', 'Active')
            ->orderBy('full_name')
            ->get();

        return view('parklane.nurses.digital_rosters.create', compact('nurses'));
    }

    public function store(Request $request)
    {
        $nurse = Auth::guard('nurse')->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'assignments' => 'required|array|min:1',
            'assignments.*.nurse_id' => 'required|exists:nurses,id',
            'assignments.*.duty_date' => 'required|date',
            'assignments.*.shift_type' => 'required|string|max:50',
            'assignments.*.start_time' => 'nullable|date_format:H:i',
            'assignments.*.end_time' => 'nullable|date_format:H:i',
            'assignments.*.notes' => 'nullable|string|max:255',
        ]);

        $roster = NursingRoster::create([
            'hospital_id' => $nurse->hospital_id,
            'ward_id' => $nurse->ward_id,
            'created_by' => $nurse->id,
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'draft',
        ]);

        foreach ($validated['assignments'] as $assignment) {
            $roster->assignments()->create($assignment);
        }

        return redirect()->route('nurse.rosters.show', $roster)
            ->with('success', 'Duty roster created successfully.');
    }

    public function show(NursingRoster $roster)
    {
        $nurse = Auth::guard('nurse')->user();

        if (! $this->belongsToNurseAssignment($roster, $nurse)) {
            return redirect()->route('nurse.rosters.index')
                ->with('error', 'You can only view rosters for your assigned ward or clinic.');
        }

        $roster->load(['ward', 'creator', 'assignments.nurse']);

        return view('parklane.nurses.digital_rosters.show', compact('roster'));
    }

    public function submit(NursingRoster $roster)
    {
        $nurse = Auth::guard('nurse')->user();

        if (! $this->belongsToNurseAssignment($roster, $nurse)) {
            return redirect()->route('nurse.rosters.index')
                ->with('error', 'You can only submit rosters for your assigned ward or clinic.');
        }

        $roster->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Duty roster submitted for approval.');
    }

    private function belongsToNurseAssignment(NursingRoster $roster, $nurse): bool
    {
        return (string) $roster->hospital_id === (string) $nurse->hospital_id
            && (string) $roster->ward_id === (string) $nurse->ward_id;
    }
}
