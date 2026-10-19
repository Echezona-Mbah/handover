<?php

namespace App\Observers;

use App\Models\Nurses;
use App\Models\NursingRosterAssignment;
use App\Notifications\HospitalActivityNotification;
use Illuminate\Support\Facades\Auth;

class NursingRosterAssignmentObserver
{
    public function created(NursingRosterAssignment $assignment): void
    {
        $this->notifyAssignment($assignment, 'added');
    }

    public function updated(NursingRosterAssignment $assignment): void
    {
        if (! $assignment->wasChanged(['nurse_id', 'duty_date', 'shift_type', 'start_time', 'end_time', 'notes'])) {
            return;
        }

        $oldNurseId = $assignment->getOriginal('nurse_id');
        $this->notifyAssignment($assignment, 'changed');

        if ($oldNurseId && (string) $oldNurseId !== (string) $assignment->nurse_id) {
            Nurses::query()
                ->whereKey($oldNurseId)
                ->where('status', 'Active')
                ->first()
                ?->notify($this->makeNotification($assignment, 'changed'));
        }
    }

    public function deleted(NursingRosterAssignment $assignment): void
    {
        $this->notifyAssignment($assignment, 'removed');
    }

    private function notifyAssignment(NursingRosterAssignment $assignment, string $action): void
    {
        $assignment->loadMissing(['roster', 'nurse']);
        $roster = $assignment->roster;
        $nurse = $assignment->nurse;

        if (! $roster || ! $nurse || $nurse->status !== 'Active'
            || ! in_array($roster->status, ['approved', 'published'], true)) {
            return;
        }

        $nurse->notify($this->makeNotification($assignment, $action));
    }

    private function makeNotification(
        NursingRosterAssignment $assignment,
        string $action,
    ): HospitalActivityNotification {
        $roster = $assignment->roster;
        $actor = Auth::guard('nurse')->user()?->full_name ?? Auth::user()?->name ?? 'Hospital team';
        $date = $assignment->duty_date?->format('M j, Y') ?? 'the scheduled date';
        $shift = $assignment->shift_type ?? 'shift';

        return new HospitalActivityNotification(
            'Roster shift '.$action.': '.$roster->title,
            'Your '.$shift.' assignment for '.$date.' was '.$action.'.',
            $actor,
            route('nurse.rosters.show', $roster->getKey()),
        );
    }
}
