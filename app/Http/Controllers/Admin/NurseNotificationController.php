<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nurses;
use App\Notifications\AdminNurseMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NurseNotificationController extends Controller
{
    public function create(): View
    {
        $hospitalId = auth()->user()->hospital_id;
        abort_unless($hospitalId, 422, 'Your administrator account is not assigned to a hospital.');

        $nurses = Nurses::where('hospital_id', $hospitalId)
            ->orderBy('full_name')
            ->get(['id', 'full_name', 'email', 'status']);

        return view('parklane.nursing_admin.notifications.create', compact('nurses'));
    }

    public function acknowledgements(): View
    {
        $admin = auth()->user();
        abort_unless($admin->hospital_id, 422, 'Your administrator account is not assigned to a hospital.');

        $announcements = DB::table('notifications')
            ->join('nurses', function ($join) use ($admin) {
                $join->on('nurses.id', '=', 'notifications.notifiable_id')
                    ->where('notifications.notifiable_type', Nurses::class)
                    ->where('nurses.hospital_id', $admin->hospital_id);
            })
            ->where('notifications.type', AdminNurseMessage::class)
            ->where('notifications.data->sender_id', $admin->id)
            ->whereJsonContains('notifications.data->requires_acknowledgement', true)
            ->orderByDesc('notifications.created_at')
            ->select([
                'notifications.id',
                'notifications.data',
                'notifications.read_at',
                'notifications.acknowledged_at',
                'notifications.created_at',
                'nurses.full_name',
                'nurses.email',
            ])
            ->paginate(25);

        $announcements->getCollection()->transform(function ($announcement) {
            $announcement->data = json_decode($announcement->data, true, 512, JSON_THROW_ON_ERROR);

            return $announcement;
        });

        return view('parklane.nursing_admin.notifications.acknowledgements', compact('announcements'));
    }

    public function store(Request $request): RedirectResponse
    {
        $hospitalId = auth()->user()->hospital_id;
        abort_unless($hospitalId, 422, 'Your administrator account is not assigned to a hospital.');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:5000'],
            'requires_acknowledgement' => ['sometimes', 'boolean'],
            'send_to_all' => ['sometimes', 'boolean'],
            'nurse_ids' => ['required_unless:send_to_all,1', 'array', 'min:1'],
            'nurse_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('nurses', 'id')->where('hospital_id', $hospitalId),
            ],
        ]);

        $recipients = Nurses::where('hospital_id', $hospitalId)
            ->when(
                empty($validated['send_to_all']),
                fn ($query) => $query->whereIn('id', $validated['nurse_ids'])
            )
            ->get();

        if ($recipients->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors(['nurse_ids' => 'There are no nurses in your hospital to notify.']);
        }

        $senderName = auth()->user()->name;
        $requiresAcknowledgement = (bool) ($validated['requires_acknowledgement'] ?? false);
        foreach ($recipients as $nurse) {
            $nurse->notify(new AdminNurseMessage(
                $validated['title'],
                $validated['message'],
                $senderName,
                (int) auth()->id(),
                $requiresAcknowledgement,
            ));
        }

        return redirect()
            ->route('admin.nurse-notifications.create')
            ->with('success', 'Notification and email sent to '.$recipients->count().' nurse(s).');
    }
}
