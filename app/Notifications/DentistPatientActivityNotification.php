<?php

namespace App\Notifications;

use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DentistPatientActivityNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Patient $patient,
        private readonly string $title,
        private readonly string $message,
        private readonly string $activity
    ) {}

    public function via(object $notifiable): array
    {
        return filter_var($notifiable->email ?? null, FILTER_VALIDATE_EMAIL)
            ? ['database', 'mail']
            : ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'activity' => $this->activity,
            'patient_id' => $this->patient->id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $patientRoute = $notifiable instanceof Doctor
            ? 'doctor.show_patient'
            : 'dentist.show_patient';

        return (new MailMessage)
            ->subject($this->title)
            ->greeting('Hello ' . ($notifiable->full_name ?? ''))
            ->line($this->message)
            ->line('Patient: ' . ($this->patient->full_name ?: 'Name not recorded'))
            ->line('Patient number: ' . ($this->patient->pt_number ?: $this->patient->patient_number))
            ->action('Open patient record', route($patientRoute, $this->patient))
            ->line('This is an automated hospital activity notification.');
    }
}
