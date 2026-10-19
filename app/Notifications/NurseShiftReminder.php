<?php

namespace App\Notifications;

use App\Models\Nurses;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NurseShiftReminder extends Notification
{
    use Queueable;

    public function __construct(
        private readonly int $assignmentId,
        private readonly string $rosterTitle,
        private readonly string $shiftType,
        private readonly string $shiftDate,
        private readonly ?string $startTime,
        private readonly ?string $endTime,
        private readonly string $url,
    ) {}

    public function via(Nurses $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(Nurses $notifiable): array
    {
        return [
            'title' => 'Shift reminder: '.$this->rosterTitle,
            'message' => $this->message(),
            'sender_name' => 'Hospital roster',
            'url' => $this->url,
            'assignment_id' => $this->assignmentId,
        ];
    }

    public function toMail(Nurses $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Shift reminder: '.$this->rosterTitle)
            ->greeting('Hello '.$notifiable->full_name.',')
            ->line($this->message())
            ->action('View your roster', $this->url)
            ->salutation('Regards, Hospital roster');
    }

    private function message(): string
    {
        $time = $this->startTime
            ? ' from '.$this->startTime.($this->endTime ? ' to '.$this->endTime : '')
            : '';

        return 'You are scheduled for a '.$this->shiftType.' shift today ('.$this->shiftDate.')'.$time.'.';
    }
}
