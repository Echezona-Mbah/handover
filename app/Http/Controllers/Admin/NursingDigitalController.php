<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NursingReport;
use App\Models\NursingReportReview;
use App\Models\NursingRoster;
use App\Models\NursingStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NursingDigitalController extends Controller
{
    public function reports()
    {
        $reports = NursingReport::with(['nurse', 'ward', 'patient'])
            ->latest()
            ->paginate(20);

        return view('parklane.nursing_admin.reports', compact('reports'));
    }

    public function showReport(NursingReport $report)
    {
        $report->load(['nurse', 'ward', 'patient', 'reviews.reviewer']);

        return view('parklane.nursing_admin.report_show', compact('report'));
    }

    public function reviewReport(Request $request, NursingReport $report)
    {
        $validated = $request->validate([
            'action' => 'required|in:comment,request_clarification,reviewed,approved',
            'comment' => 'required|string',
        ]);

        NursingReportReview::create([
            'nursing_report_id' => $report->id,
            'reviewed_by' => Auth::id(),
            'action' => $validated['action'],
            'comment' => $validated['comment'],
            'snapshot' => $report->toArray(),
        ]);

        $status = match ($validated['action']) {
            'request_clarification' => 'needs_clarification',
            'approved' => 'approved',
            'reviewed' => 'reviewed',
            default => $report->status,
        };

        $report->update([
            'status' => $status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Review saved successfully.');
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
        $rosters = NursingRoster::with(['ward', 'creator'])
            ->latest()
            ->paginate(20);

        return view('parklane.nursing_admin.rosters', compact('rosters'));
    }

    public function showRoster(NursingRoster $roster)
    {
        $roster->load(['ward', 'creator', 'assignments.nurse']);

        return view('parklane.nursing_admin.roster_show', compact('roster'));
    }

    public function approveRoster(Request $request, NursingRoster $roster)
    {
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

