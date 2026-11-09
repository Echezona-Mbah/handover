<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Nurses;
use App\Models\NursingRoster;
use App\Models\NursingRosterAssignment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
        abort_unless($nurse->hospital_id && $nurse->ward_id, 403, 'Your nurse account must be assigned to a hospital ward before creating a roster.');

        $nurses = Nurses::with('NursingRole')
            ->where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->where('status', 'Active')
            ->orderBy('full_name')
            ->get();

        $today = now();
        $startDate = $today->day >= 25
            ? $today->copy()->day(25)
            : $today->copy()->subMonthNoOverflow()->day(25);
        $endDate = $startDate->copy()->addMonthNoOverflow()->day(24);
        $days = collect(CarbonPeriod::create($startDate->toDateString(), $endDate->toDateString()));
        $ward = $nurse->ward;
        $hospital = $nurse->hospitals;

        return view('parklane.nurses.digital_rosters.create', compact(
            'nurses', 'days', 'startDate', 'endDate', 'ward', 'hospital'
        ));
    }

    public function store(Request $request)
    {
        $nurse = Auth::guard('nurse')->user();

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'schedule_json' => 'required|json|max:200000',
        ]);

        if (Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) > 30) {
            throw ValidationException::withMessages(['end_date' => 'A roster period cannot exceed 31 days.']);
        }

        $schedule = json_decode($validated['schedule_json'], true);
        if (! is_array($schedule)) {
            throw ValidationException::withMessages(['schedule_json' => 'The roster schedule is invalid. Please review the shifts and try again.']);
        }

        $schedule = Validator::make(['schedule' => $schedule], [
            'schedule' => 'required|array|min:1',
            'schedule.*' => 'required|array|min:1',
            'schedule.*.*' => ['nullable', Rule::in(['', 'D', 'M', 'A', 'N', 'O', 'AL', 'SL'])],
        ])->validate()['schedule'];

        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = Carbon::parse($validated['end_date'])->startOfDay();
        $eligibleNurses = Nurses::where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->where('status', 'Active')
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();
        $expectedDates = collect(CarbonPeriod::create($startDate, $endDate))
            ->map(fn (Carbon $date) => $date->toDateString())
            ->all();
        if (count($schedule) !== count($eligibleNurses)) {
            throw ValidationException::withMessages(['schedule_json' => 'The roster must include every active nurse in your ward.']);
        }

        $rosterAssignments = [];
        $hasScheduledShift = false;

        foreach ($schedule as $nurseId => $nurseSchedule) {
            if (! ctype_digit((string) $nurseId) || ! in_array((string) $nurseId, $eligibleNurses, true)) {
                throw ValidationException::withMessages(['schedule_json' => 'The roster includes a nurse outside your ward.']);
            }
            if (count($nurseSchedule) !== count($expectedDates)
                || array_diff($expectedDates, array_keys($nurseSchedule)) !== []) {
                throw ValidationException::withMessages(['schedule_json' => 'Every nurse must have one roster cell for every date in the selected period.']);
            }

            foreach ($nurseSchedule as $date => $shift) {
                if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $date)) {
                    throw ValidationException::withMessages(['schedule_json' => 'The roster contains an invalid date.']);
                }
                $dutyDate = Carbon::createFromFormat('!Y-m-d', (string) $date);
                if (! $dutyDate || $dutyDate->format('Y-m-d') !== $date || $dutyDate->lt($startDate) || $dutyDate->gt($endDate)) {
                    throw ValidationException::withMessages(['schedule_json' => 'The roster contains a date outside the selected period.']);
                }

                $hasScheduledShift = $hasScheduledShift || filled($shift);
                $rosterAssignments[] = [
                    'nurse_id' => (int) $nurseId,
                    'duty_date' => $dutyDate->toDateString(),
                    'shift_type' => filled($shift) ? $shift : '—',
                ];
            }
        }

        if (! $hasScheduledShift) {
            throw ValidationException::withMessages(['schedule_json' => 'Select at least one shift before saving the roster.']);
        }

        $title = $validated['title'] ?: sprintf(
            'Duty Roster %s - %s to %s',
            $nurse->ward?->name ?? 'Ward',
            Carbon::parse($validated['start_date'])->format('d M Y'),
            Carbon::parse($validated['end_date'])->format('d M Y')
        );

        $roster = DB::transaction(function () use ($nurse, $validated, $rosterAssignments, $title) {
            $roster = NursingRoster::create([
                'hospital_id' => $nurse->hospital_id,
                'ward_id' => $nurse->ward_id,
                'created_by' => $nurse->id,
                'title' => $title,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => 'draft',
            ]);

            $now = now();
            $assignments = collect($rosterAssignments)->map(fn (array $assignment) => [
                ...$assignment,
                'nursing_roster_id' => $roster->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            foreach ($assignments->chunk(100) as $chunk) {
                NursingRosterAssignment::insert($chunk->all());
            }

            return $roster;
        });

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

        $roster->load(['ward.hospital', 'creator', 'assignments.nurse.NursingRole']);

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
