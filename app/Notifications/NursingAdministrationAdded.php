<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NursingAdministrationAdded extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $administrationName,
        private readonly string $senderName = 'Hospital administration',
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New nursing administration available',
            'message' => $this->administrationName.' is now available when submitting a nursing report.',
            'sender_name' => $this->senderName,
            'ward_name' => $notifiable->ward?->name ?? 'Hospital-wide',
            'detail' => 'Nursing administration available for reports.',
            'administration_name' => $this->administrationName,
        ];
    }
}
