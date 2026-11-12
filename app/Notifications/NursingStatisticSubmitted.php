<?php

namespace App\Notifications;

use App\Models\Nurses;
use App\Models\NursingStatistic;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NursingStatisticSubmitted extends Notification
{
    use Queueable;

    public function __construct(private readonly NursingStatistic $statistic) {}

    public function via(object $notifiable): array
    {
        return filled($notifiable->email ?? null) ? ['database', 'mail'] : ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $nurse = $this->statistic->nurse;
        $senderName = $nurse?->full_name ?? 'A nurse';

        return [
            'title' => 'Nursing statistics received from '.$senderName,
            'message' => sprintf(
                'Submitted by %s from %s; statistics for %s on %s were sent to your ward.',
                $senderName,
                $nurse?->ward?->name ?? 'an unknown ward',
                $this->statistic->ward?->name ?? 'a ward',
                $this->statistic->stats_date?->format('d M Y') ?? ''
            ),
            'sender_name' => $senderName,
            'source_ward' => $nurse?->ward?->name ?? 'Unknown ward',
            'ward_name' => $this->statistic->ward?->name ?? 'Unknown ward',
            'detail' => $this->details(),
            'stats_date' => $this->statistic->stats_date?->format('d M Y'),
            'statistic_id' => $this->statistic->id,
            'url' => $this->urlFor($notifiable),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $notifiable instanceof Nurses ? $notifiable->full_name : $notifiable->name;

        return (new MailMessage)
            ->subject('Nursing statistics sent to your ward')
            ->greeting('Hello '.$name.',')
            ->line('Submitted by '.($this->statistic->nurse?->full_name ?? 'a nurse').' from '.($this->statistic->nurse?->ward?->name ?? 'an unknown ward').'.')
            ->line(sprintf('Statistics for %s on %s were sent to your ward.', $this->statistic->ward?->name ?? 'your ward', $this->statistic->stats_date?->format('d M Y') ?? ''))
            ->line($this->details())
            ->action('View statistics', $this->urlFor($notifiable));
    }

    private function details(): string
    {
        return sprintf(
            'Admissions: %d; discharges: %d; transfers: %d; deaths: %d; beds occupied: %d; beds vacant: %d; clinic attendance: %d; specialized services: %d.%s',
            $this->statistic->admissions,
            $this->statistic->discharges,
            $this->statistic->transfers,
            $this->statistic->deaths,
            $this->statistic->bed_occupied,
            $this->statistic->bed_vacant,
            $this->statistic->clinic_attendance,
            $this->statistic->specialized_service_count,
            filled($this->statistic->notes) ? ' Notes: '.\Illuminate\Support\Str::limit($this->statistic->notes, 500) : ''
        );
    }

    private function urlFor(object $notifiable): string
    {
        return $notifiable instanceof Nurses
            ? route('nurse.statistics.show', $this->statistic)
            : route('admin.nursing.statistics');
    }
}
