<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NursingReport;
use App\Models\NursingReportReview;
use App\Models\NursingRoster;
use App\Models\NursingStatistic;
use App\Models\NursingEscalation;
use App\Models\User;
use App\Notifications\NursingReportReviewed;
use App\Services\NursingHierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NursingDigitalController extends Controller
{
    public function reports()
    {
        $reports = NursingHierarchy::visibleReports(Auth::user())->with(['nurse', 'ward', 'patient', 'assignedAdmin'])
            ->latest()
            ->paginate(20);

        return view('parklane.nursing_admin.reports', compact('reports'));
    }

    public function showReport(NursingReport $report)
    {
        abort_unless(NursingHierarchy::canViewReport(Auth::user(), $report), 403);
        $report->load(['nurse', 'ward', 'patient', 'assignedAdmin', 'reviews.reviewer']);

        return view('parklane.nursing_admin.report_show', compact('report'));
    }

    public function reviewReport(Request $request, NursingReport $report)
    {
        abort_unless(NursingHierarchy::canViewReport(Auth::user(), $report), 403);
        abort_unless(in_array(NursingHierarchy::role(Auth::user()), ['hns', 'unit_head'], true), 403);

        if ($request->filled('decision')) {
            $request->merge(['action' => $request->input('decision')]);
        }

        $validated = $request->validate([
            'action' => 'required|in:comment,request_clarification,reviewed,approved,declined',
            'comment' => 'required_unless:action,approved,declined|nullable|string',
        ]);
        $comment = $validated['comment'] ?? '';

        NursingReportReview::create([
            'nursing_report_id' => $report->id,
            'reviewed_by' => Auth::id(),
            'action' => $validated['action'],
            'comment' => $comment,
            'snapshot' => $report->toArray(),
        ]);

        $status = match ($validated['action']) {
            'request_clarification' => 'needs_clarification',
            'approved' => 'approved',
            'declined' => 'declined',
            'reviewed' => 'reviewed',
            default => $report->status,
        };

        $report->update([
            'status' => $status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        NursingHierarchy::audit(Auth::user(), 'report_status_changed', $report, ['status' => $status]);

        $report->nurse?->notify(new NursingReportReviewed(
            $report,
            $validated['action'],
            $comment,
            Auth::user()->name,
        ));
        if (NursingHierarchy::role(Auth::user()) === 'unit_head' && $report->ward_id) {
            $report->ward?->nurses()
                ->where('nursing_hierarchy_role', 'ward_supervision')
                ->where('id', '!=', $report->nurse_id)
                ->get()
                ->each(fn ($supervisor) => $supervisor->notify(new NursingReportReviewed(
                    $report,
                    $validated['action'],
                    $validated['comment'],
                    Auth::user()->name,
                )));
        }

        return back()->with('success', 'Review saved successfully.');
    }

    public function escalateReport(Request $request, NursingReport $report)
    {
        $actor = Auth::user();
        abort_unless(NursingHierarchy::isControl($actor) && NursingHierarchy::canViewReport($actor, $report), 403);
        $data = $request->validate(['reason' => 'required|string|max:10000']);
        $escalation = NursingEscalation::create($data + ['nursing_report_id' => $report->id, 'escalated_by_type' => 'user', 'escalated_by_id' => $actor->id]);
        User::where('hospital_id', $actor->hospital_id)->where('nursing_hierarchy_role', 'hns')->each(fn (User $hns) => $hns->notify(new \App\Notifications\NursingEscalated($escalation)));
        NursingHierarchy::audit($actor, 'report_escalated', $escalation, ['report_id' => $report->id]);
        return back()->with('success', 'Report escalated to HNS.');
    }

    public function statistics()
    {
        $statistics = NursingStatistic::with(['nurse', 'ward'])
            ->latest('stats_date')
            ->paginate(20);

        return view('parklane.nursing_admin.statistics', compact('statistics'));
    }

    public function verifyStatistic(NursingStatistic $statistic)
    {
        $statistic->update([
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Statistics verified successfully.');
    }

    public function rosters()
    {
        $actor = Auth::user();
        abort_unless(NursingHierarchy::canManageNursingDirectory($actor), 403);

        $rosters = NursingRoster::with(['ward', 'creator'])
            ->where('hospital_id', $actor->hospital_id)
            ->whereIn('ward_id', NursingHierarchy::wardsFor($actor))
            ->latest()
            ->paginate(20);

        return view('parklane.nursing_admin.rosters', compact('rosters'));
    }

    public function showRoster(NursingRoster $roster)
    {
        $actor = Auth::user();
        abort_unless(NursingHierarchy::canManageNursingDirectory($actor)
            && (int) $roster->hospital_id === (int) $actor->hospital_id
            && in_array((int) $roster->ward_id, NursingHierarchy::wardsFor($actor), true), 403);

        $roster->load(['ward.hospital', 'creator', 'assignments.nurse.NursingRole']);

        return view('parklane.nursing_admin.roster_show', compact('roster'));
    }

    public function approveRoster(Request $request, NursingRoster $roster)
    {
        $actor = Auth::user();
        abort_unless(NursingHierarchy::canManageNursingDirectory($actor)
            && (int) $roster->hospital_id === (int) $actor->hospital_id
            && in_array((int) $roster->ward_id, NursingHierarchy::wardsFor($actor), true), 403);

        $validated = $request->validate([
            'status' => 'required|in:approved,published',
        ]);

        $roster->update([
            'status' => $validated['status'],
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Roster updated successfully.');
    }
}
