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
        return [
            'title' => 'Nursing statistics received',
            'message' => sprintf(
                'Statistics for %s on %s were sent to your ward.',
                $this->statistic->ward?->name ?? 'a ward',
                $this->statistic->stats_date?->format('d M Y') ?? ''
            ),
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
            ->line(sprintf(
                'Nursing statistics for %s on %s have been sent to your ward.',
                $this->statistic->ward?->name ?? 'your ward',
                $this->statistic->stats_date?->format('d M Y') ?? ''
            ))
            ->action('View statistics', $this->urlFor($notifiable));
    }

    private function urlFor(object $notifiable): string
    {
        return $notifiable instanceof Nurses
            ? route('nurse.statistics.show', $this->statistic)
            : route('admin.nursing.statistics');
    }
}
