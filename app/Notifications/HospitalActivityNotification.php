<?php

namespace App\Notifications;

use App\Models\Nurses;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HospitalActivityNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $message,
        private readonly string $senderName,
        private readonly ?string $url = null,
        private readonly bool $urgent = false,
        private readonly ?string $urgency = null,
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
            'url' => $this->url,
            'urgent' => $this->urgent,
            'urgency' => $this->urgency,
        ];
    }

    public function toMail(Nurses $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->title)
            ->greeting('Hello '.$notifiable->full_name.',')
            ->line($this->urgent ? 'URGENT: '.$this->message : $this->message);

        if ($this->url) {
            $mail->action($this->urgent ? 'View urgent alert' : 'View update', $this->url);
        }

        return $mail->salutation('Regards, '.$this->senderName);
    }
}
