<?php

namespace App\Notifications;

use App\Models\NursingReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NursingReportSubmitted extends Notification
{
    use Queueable;

    public function __construct(private readonly NursingReport $report) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New nursing report awaiting review',
            'message' => sprintf('%s submitted a %s report.', $this->report->nurse?->full_name ?? 'A nurse', str_replace('_', ' ', $this->report->report_type)),
            'report_id' => $this->report->id,
            'target_admin_role' => $this->report->target_admin_role,
            'url' => route('admin.nursing.reports.show', $this->report),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New nursing report awaiting your review')
            ->greeting('Hello '.$notifiable->name.',')
            ->line(($this->report->nurse?->full_name ?? 'A nurse').' has submitted a nursing report for your review.')
            ->action('Review report', route('admin.nursing.reports.show', $this->report))
            ->line('Please approve or decline the report after reviewing it.');
    }
}
