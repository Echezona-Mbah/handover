<?php

namespace App\Mail;

use App\Models\Doctor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public Doctor $doctor;
    public string $resetToken;

    public function __construct(Doctor $doctor, string $resetToken)
    {
        $this->doctor = $doctor;
        $this->resetToken = $resetToken;
    }

    public function build()
    {
        return $this->subject('Welcome — Set Up Your Password')
            ->view('parklane.emails.doctor_registered')
            ->with([
                'doctor' => $this->doctor,
                'resetUrl' => route('doctor.password.setup', ['token' => $this->resetToken]),
            ]);
    }
}