<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\NursingReport;
use App\Models\Nurses;
use App\Models\User;
use App\Notifications\NursingReportSubmitted;
use App\Services\NursingHierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NursingReportController extends Controller
{
    public function index()
    {
        $nurse = Auth::guard('nurse')->user();

        $reports = NursingHierarchy::visibleReports($nurse)->with(['patient', 'ward'])
            ->latest()
            ->paginate(15);

        return view('parklane.nurses.digital_reports.index', compact('reports'));
    }

    public function create()
    {
        $nurse = Auth::guard('nurse')->user();
        $involvedNurses = Nurses::where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->orderBy('full_name')
            ->get();

        $unitHead = $nurse->ward?->unitHead;
        $hasHns = User::where('hospital_id', $nurse->hospital_id)
            ->get()
            ->contains(fn (User $user) => NursingHierarchy::isHns($user));

        return view('parklane.nurses.digital_reports.create', compact('involvedNurses', 'unitHead', 'hasHns'));
    }

    public function store(Request $request)
    {
        $nurse = Auth::guard('nurse')->user();
        $isIncident = $request->input('report_type') === 'incident';
        $validated = $request->validate([
            'report_type' => ['required', \Illuminate\Validation\Rule::in(['daily_shift', 'incident', 'administrative', 'referral'])],
            'report_date' => 'required|date',
            'shift' => 'nullable|string|max:50',
            'summary' => ['required', 'string'],
            'incidents' => ['nullable', 'string'],
            'referrals' => 'nullable|string',
            'administrative_notes' => 'nullable|string',
            'involved_nurse_ids' => [$isIncident ? 'required' : 'nullable', 'array', $isIncident ? 'min:1' : 'min:0'],
            'involved_nurse_ids.*' => [
                'integer',
                \Illuminate\Validation\Rule::exists('nurses', 'id')->where(fn ($query) => $query
                    ->where('hospital_id', $nurse->hospital_id)
                    ->where('ward_id', $nurse->ward_id)),
            ],
            'recipient_target' => ['required', \Illuminate\Validation\Rule::in(['hns', 'unit_head', 'both'])],
        ]);
        $unitHead = $nurse->ward?->unitHead;
        $hnsRecipients = User::where('hospital_id', $nurse->hospital_id)
            ->get()
            ->filter(fn (User $user) => NursingHierarchy::isHns($user));
        $recipientTarget = $validated['recipient_target'];
        abort_unless($recipientTarget === 'hns' || $recipientTarget === 'unit_head' || ($hnsRecipients->isNotEmpty() && $unitHead), 422, 'Your hospital must have an HNS account and unit head to send to both.');
        abort_unless($recipientTarget !== 'hns' || $hnsRecipients->isNotEmpty(), 422, 'No HNS Office account is configured for your hospital.');
        abort_unless($recipientTarget !== 'unit_head' || $unitHead, 422, 'Your ward has no assigned unit head.');

        if ($isIncident) {
            $names = Nurses::where('hospital_id', $nurse->hospital_id)
                ->where('ward_id', $nurse->ward_id)
                ->whereIn('id', $validated['involved_nurse_ids'])
                ->orderBy('full_name')
                ->pluck('full_name')
                ->all();
            $validated['incidents'] = 'Involved nurses: '.implode(', ', $names);
            $validated['patient_id'] = null;
            $validated['referrals'] = null;
            $validated['administrative_notes'] = null;
        } else {
            if (! $isIncident) {
                $validated['incidents'] = null;
            }
            if ($validated['report_type'] !== 'referral') {
                $validated['referrals'] = null;
            }
            if ($validated['report_type'] !== 'administrative') {
                $validated['administrative_notes'] = null;
            }
        }
        unset($validated['involved_nurse_ids']);

        $validated['hospital_id'] = $nurse->hospital_id;
        $validated['ward_id'] = $nurse->ward_id;
        $validated['nurse_id'] = $nurse->id;
        $validated['status'] = 'submitted';
        $validated['submitted_at'] = now();
        $validated['recipient_type'] = 'user';
        $validated['recipient_role'] = match ($recipientTarget) {
            'hns' => 'hns',
            'unit_head' => 'unit_head',
            default => 'hns_and_unit_head',
        };
        $validated['recipient_id'] = $recipientTarget === 'hns' ? $hnsRecipients->first()->id : $unitHead->id;
        $validated['target_admin_role'] = null;
        unset($validated['recipient_target']);

        $report = NursingReport::create($validated);

        $report->load('nurse');
        $recipients = match ($recipientTarget) {
            'hns' => $hnsRecipients,
            'unit_head' => collect([$unitHead]),
            default => $hnsRecipients->push($unitHead)->unique('id'),
        };
        $recipients->each(fn (User $admin) => $admin->notify(new NursingReportSubmitted($report)));
        NursingHierarchy::audit($nurse, 'report_submitted', $report, ['recipient_role' => $validated['recipient_role'], 'recipient_id' => $validated['recipient_id']]);

        return redirect()->route('nurse.reports.show', $report)
            ->with('success', 'Nursing report sent to '.match ($recipientTarget) {
                'hns' => 'the HNS Office.',
                'unit_head' => 'your unit head.',
                default => 'the HNS Office and your unit head.',
            });
    }

    public function show(NursingReport $report)
    {
        $nurse = Auth::guard('nurse')->user();

        if (! NursingHierarchy::canViewReport($nurse, $report)) {
            return redirect()->route('nurse.reports.index')
                ->with('error', 'You can only view reports for your assigned ward or clinic.');
        }

        $report->load(['patient', 'ward', 'nurse', 'assignedAdmin', 'reviews.reviewer']);

        return view('parklane.nurses.digital_reports.show', compact('report'));
    }

    private function belongsToNurseAssignment(NursingReport $report, $nurse): bool
    {
        return (string) $report->hospital_id === (string) $nurse->hospital_id
            && (string) $report->ward_id === (string) $nurse->ward_id;
    }
}
