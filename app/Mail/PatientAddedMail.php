<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientAddedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $doctor;

    public function __construct($patient, $doctor)
    {
        $this->patient = $patient;
        $this->doctor = $doctor;
    }

    public function build()
    {
        return $this->subject('New Patient Added to Your Unit')
            ->view('parklane.emails.patient_added');
    }
}
