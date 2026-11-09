<?php

namespace App\Notifications;

use App\Models\Nurses;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NursingWardTransferNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $message,
        private readonly int $patientId,
    ) {}

    public function via(object $notifiable): array
    {
        return filled($notifiable->email ?? null) ? ['database', 'mail'] : ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'patient_id' => $this->patientId,
            'url' => route('nurse.patient'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $notifiable instanceof Nurses ? $notifiable->full_name : $notifiable->name;

        return (new MailMessage)
            ->subject($this->title)
            ->greeting('Hello '.$name.',')
            ->line($this->message)
            ->action('Open ward patients', route('nurse.patient'));
    }
}
