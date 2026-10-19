<?php

namespace App\Notifications;

use App\Models\Nurses;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNurseMessage extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $message,
        private readonly string $senderName,
        private readonly int $senderId,
        private readonly bool $requiresAcknowledgement,
    ) {}

    public function via(Nurses $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(Nurses $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'sender_name' => $this->senderName,
            'sender_id' => $this->senderId,
            'requires_acknowledgement' => $this->requiresAcknowledgement,
        ];
    }

    public function toMail(Nurses $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Admin notification: '.$this->title)
            ->greeting('Hello '.$notifiable->full_name.',')
            ->line($this->message);

        if ($this->requiresAcknowledgement) {
            $mail->line('Please sign in to the nurse portal to acknowledge this announcement.');
        }

        return $mail->salutation('Regards, '.$this->senderName);
    }
}
