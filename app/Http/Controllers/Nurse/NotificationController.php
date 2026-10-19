<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $nurse = Auth::guard('nurse')->user();
        $notifications = $nurse->notifications()->latest()->paginate(20);

        return view('parklane.nurses.notifications.index', compact('notifications'));
    }

    public function markAsRead(string $notification): RedirectResponse
    {
        $nurse = Auth::guard('nurse')->user();
        $nurse->unreadNotifications()
            ->whereKey($notification)
            ->firstOrFail()
            ->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    public function acknowledge(string $notification): RedirectResponse
    {
        $nurse = Auth::guard('nurse')->user();
        $item = $nurse->notifications()->whereKey($notification)->firstOrFail();

        abort_unless(
            (bool) ($item->data['requires_acknowledgement'] ?? false),
            404
        );

        $item->markAsRead();
        $item->forceFill(['acknowledged_at' => now()])->save();

        return back()->with('success', 'Announcement acknowledged.');
    }
}
