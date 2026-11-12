<?php

namespace App\Notifications;

use App\Models\NursingReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NursingReportReviewed extends Notification
{
    use Queueable;

    public function __construct(
        private readonly NursingReport $report,
        private readonly string $action,
        private readonly string $comment,
        private readonly string $reviewerName,
    ) {}

    public function via(object $notifiable): array
    {
        return filled($notifiable->email ?? null) ? ['database', 'mail'] : ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $action = ucwords(str_replace('_', ' ', $this->action));

        return [
            'title' => 'Your nursing report was '.$action,
            'message' => $this->comment,
            'sender_name' => $this->reviewerName,
            'ward_name' => $this->report->ward?->name ?? 'Unknown ward',
            'detail' => str_replace('_', ' ', $this->report->report_type).' report: '.\Illuminate\Support\Str::limit($this->report->summary, 180),
            'report_id' => $this->report->id,
            'status' => $this->report->status,
            'url' => route('nurse.reports.show', $this->report),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('A nursing administrator responded to a report')
            ->greeting('Hello '.($notifiable->full_name ?? 'Nurse').',')
            ->line('Your nursing report was '.ucwords(str_replace('_', ' ', $this->action)).'.');

        $mail->line('Ward: '.($this->report->ward?->name ?? 'Unknown ward'))
            ->line('Report: '.\Illuminate\Support\Str::limit($this->report->summary, 1000));

        if (filled($this->comment)) {
            $mail->line($this->comment);
        }

        return $mail->action('View report', route('nurse.reports.show', $this->report));
    }
}
