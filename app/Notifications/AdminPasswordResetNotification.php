<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminPasswordResetNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);
        $expiryMinutes = config('auth.passwords.users.expire');
        $expiry = $expiryMinutes % 1440 === 0
            ? intdiv($expiryMinutes, 1440).' days'
            : $expiryMinutes.' minutes';

        return (new MailMessage)
            ->subject('Set up or reset your administrator password')
            ->greeting('Hello '.($notifiable->name ?? 'Administrator').',')
            ->line('Your administrator account is ready to use.')
            ->line('Login email: '.$notifiable->getEmailForPasswordReset())
            ->line('Use the secure link below to set or reset your password. This link expires in '.$expiry.' and can only be used once.')
            ->action('Set up password', $url)
            ->line('If you did not expect this account, please contact your hospital administrator.');
    }
}
