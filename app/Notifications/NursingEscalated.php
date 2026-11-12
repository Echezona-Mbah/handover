<?php
namespace App\Notifications;

use App\Models\NursingReport;
use App\Models\User;
use App\Models\NursingEscalation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NursingEscalated extends Notification
{
    use Queueable;

    public function __construct(private NursingEscalation $escalation) {}

    public function via(object $notifiable): array
    {
        return filled($notifiable->email ?? null) ? ['database', 'mail'] : ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $report = $this->report();

        return [
            'title' => 'Nursing report escalated',
            'message' => \Illuminate\Support\Str::limit($this->escalation->reason, 240),
            'sender_name' => $this->senderName(),
            'ward_name' => $report?->ward?->name ?? 'Unknown ward',
            'detail' => $report ? \Illuminate\Support\Str::limit($report->summary, 180) : 'A nursing query requires review.',
            'escalation_id' => $this->escalation->id,
            'report_id' => $this->escalation->nursing_report_id,
            'url' => $report ? route('admin.nursing.reports.show', $report) : route('admin.nursing.dashboard'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $report = $this->report();

        return (new MailMessage)
            ->subject('Nursing report escalated')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('From: '.$this->senderName())
            ->line('Ward: '.($report?->ward?->name ?? 'Unknown ward'))
            ->line($report ? 'Report: '.\Illuminate\Support\Str::limit($report->summary, 1000) : 'A nursing query requires review.')
            ->line('Reason: '.\Illuminate\Support\Str::limit($this->escalation->reason, 1000))
            ->action('Review escalation', $report ? route('admin.nursing.reports.show', $report) : route('admin.nursing.dashboard'));
    }

    private function report(): ?NursingReport
    {
        return $this->escalation->nursing_report_id
            ? NursingReport::with(['ward', 'nurse'])->find($this->escalation->nursing_report_id)
            : null;
    }

    private function senderName(): string
    {
        return $this->escalation->escalated_by_type === 'user'
            ? (User::find($this->escalation->escalated_by_id)?->name ?? 'Nursing administrator')
            : 'Nursing staff';
    }
}
