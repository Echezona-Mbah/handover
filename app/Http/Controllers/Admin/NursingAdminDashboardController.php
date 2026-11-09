<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nurses;
use App\Models\NursingReport;
use App\Models\NursingStatistic;
use Illuminate\Support\Facades\Auth;
use App\Services\NursingHierarchy;

class NursingAdminDashboardController extends Controller
{
    public function __invoke()
    {
        $admin = Auth::user();
        abort_unless(NursingHierarchy::role($admin), 403);

        $reports = NursingHierarchy::visibleReports($admin);

        $totalNurses = NursingHierarchy::canManageNursingDirectory($admin)
            ? NursingHierarchy::visibleNurses($admin)->count()
            : Nurses::where('hospital_id', $admin->hospital_id)->count();
        $pendingReports = (clone $reports)->whereIn('status', ['submitted', 'needs_clarification'])->count();
        $approvedReports = (clone $reports)->where('status', 'approved')->count();
        $declinedReports = (clone $reports)->where('status', 'declined')->count();
        $recentReports = $reports->with(['nurse', 'ward'])
            ->latest('submitted_at')
            ->take(8)
            ->get();
        $todayStatistics = NursingStatistic::where('hospital_id', $admin->hospital_id)
            ->whereDate('stats_date', today())
            ->count();

        return view('parklane.nursing_admin.dashboard', compact(
            'admin', 'totalNurses', 'pendingReports', 'approvedReports', 'declinedReports', 'recentReports', 'todayStatistics'
        ));
    }
}
