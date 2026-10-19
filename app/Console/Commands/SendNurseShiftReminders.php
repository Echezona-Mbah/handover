<?php

namespace App\Console\Commands;

use App\Models\NursingRosterAssignment;
use App\Notifications\NurseShiftReminder;
use Illuminate\Console\Command;

class SendNurseShiftReminders extends Command
{
    protected $signature = 'nurses:send-shift-reminders';

    protected $description = 'Email nurses a reminder for their approved shift roster assignment today';

    public function handle(): int
    {
        $assignments = NursingRosterAssignment::query()
            ->with(['nurse', 'roster'])
            ->whereDate('duty_date', today())
            ->whereHas('roster', fn ($query) => $query->whereIn('status', ['approved', 'published']))
            ->whereHas('nurse', fn ($query) => $query->where('status', 'Active'))
            ->get();

        foreach ($assignments as $assignment) {
            $nurse = $assignment->nurse;
            $roster = $assignment->roster;

            if ($nurse->notifications()
                ->where('type', NurseShiftReminder::class)
                ->where('data->assignment_id', $assignment->getKey())
                ->exists()) {
                continue;
            }

            $nurse->notify(new NurseShiftReminder(
                (int) $assignment->getKey(),
                $roster->title,
                $assignment->shift_type,
                $assignment->duty_date->format('M j, Y'),
                $assignment->start_time,
                $assignment->end_time,
                route('nurse.rosters.show', $roster->getKey()),
            ));
        }

        $this->info('Nurse shift reminders processed.');

        return self::SUCCESS;
    }
}
