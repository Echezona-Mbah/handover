<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NursePasswordResetNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('nurse.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Set up your nurse account password')
            ->greeting('Hello '.($notifiable->full_name ?? 'Nurse').',')
            ->line('Your nurse account has been created.')
            ->line('Login email: '.$notifiable->getEmailForPasswordReset())
            ->line('Use the secure link below to set or reset your password. This link expires in 2 days and can only be used once.')
            ->action('Set up password', $url)
            ->line('If you did not expect this account, please contact your hospital administrator.');
    }
}
