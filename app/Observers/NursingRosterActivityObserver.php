<?php

namespace App\Observers;

use App\Models\NursingRoster;
use App\Notifications\HospitalActivityNotification;
use Illuminate\Support\Facades\Auth;

class NursingRosterActivityObserver
{
    public function updated(NursingRoster $roster): void
    {
        if (! $roster->wasChanged('status') || ! in_array($roster->status, ['approved', 'published'], true)) {
            return;
        }

        $roster->loadMissing('assignments.nurse');
        foreach ($roster->assignments as $assignment) {
            $nurse = $assignment->nurse;
            if (! $nurse || $nurse->status !== 'Active') {
                continue;
            }

            $nurse->notify($this->notification($roster));
        }
    }

    private function notification(NursingRoster $roster): HospitalActivityNotification
    {
        $actor = Auth::guard('nurse')->user()?->full_name ?? Auth::user()?->name ?? 'Hospital team';
        $range = $roster->start_date->format('M j').'–'.$roster->end_date->format('M j, Y');

        return new HospitalActivityNotification(
            'Roster published: '.$roster->title,
            'Your duty roster is '.$roster->status.' for '.$range.'.',
            $actor,
            route('nurse.rosters.show', $roster->getKey()),
        );
    }
}
