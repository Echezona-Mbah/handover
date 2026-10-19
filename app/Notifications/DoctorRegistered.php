<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorRegistered extends Notification
{
    use Queueable;

    public function __construct(public $doctor) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome, your account is ready')
            ->greeting('Hello ' . $this->doctor->full_name)
            ->line('Your doctor account has been registered successfully.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Doctor Registered',
            'message' => 'Your account has been registered successfully.',
            'doctor_id' => $this->doctor->id,
        ];
    }
}
