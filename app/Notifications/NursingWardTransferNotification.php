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
        private readonly ?string $senderName = null,
        private readonly ?string $wardName = null,
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
            'sender_name' => $this->senderName ?? 'Nursing staff',
            'ward_name' => $this->wardName ?? 'Unknown ward',
            'detail' => 'Patient ID: '.$this->patientId,
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
            ->line('From: '.($this->senderName ?? 'Nursing staff').' · Ward: '.($this->wardName ?? 'Unknown ward'))
            ->line($this->message)
            ->line('Patient ID: '.$this->patientId)
            ->action('Open ward patients', route('nurse.patient'));
    }
}
