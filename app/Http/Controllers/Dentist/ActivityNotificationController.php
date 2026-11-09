<?php

namespace App\Http\Controllers\Dentist;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ActivityNotificationController extends Controller
{
    public function index(): View
    {
        $dentist = Auth::guard('dentist')->user();
        $notifications = $dentist->notifications()->latest()->paginate(15);
        $unreadActivityCount = $dentist->unreadNotifications()->count();

        return view('parklane.dentist.notifications', compact(
            'notifications',
            'unreadActivityCount'
        ));
    }

    public function markAllAsRead(): RedirectResponse
    {
        $dentist = Auth::guard('dentist')->user();

        foreach ($dentist->unreadNotifications()->get() as $notification) {
            $notification->markAsRead();
        }

        return redirect()->route('dentist.activity-notifications.index')
            ->with('success', 'All notifications have been marked as read.');
    }

    public function openDentist(string $notification): RedirectResponse
    {
        $dentist = Auth::guard('dentist')->user();
        $activity = $dentist->notifications()->findOrFail($notification);
        $activity->markAsRead();

        return redirect()->route('dentist.show_patient', $activity->data['patient_id']);
    }

    public function openDoctor(string $notification): RedirectResponse
    {
        $doctor = Auth::guard('doctor')->user();
        $activity = $doctor->notifications()->findOrFail($notification);
        $activity->markAsRead();

        return redirect()->route('doctor.show_patient', $activity->data['patient_id']);
    }
}
