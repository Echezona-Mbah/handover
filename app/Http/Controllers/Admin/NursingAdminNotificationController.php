<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\NursingReportSubmitted;
use App\Services\NursingHierarchy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NursingAdminNotificationController extends Controller
{
    public function index(): View
    {
        $admin = Auth::user();
        abort_unless(NursingHierarchy::role($admin), 403);

        $notifications = $admin->notifications()
            ->where('type', NursingReportSubmitted::class)
            ->latest()
            ->paginate(20);

        return view('parklane.nursing_admin.notifications.index', compact('notifications'));
    }

    public function markAsRead(string $notification): RedirectResponse
    {
        $admin = Auth::user();
        abort_unless(NursingHierarchy::role($admin), 403);

        $admin->unreadNotifications()
            ->whereKey($notification)
            ->firstOrFail()
            ->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }
}
