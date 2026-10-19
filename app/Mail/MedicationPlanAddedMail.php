<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicationPlanAddedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $doctor;
    public $medication;
    public $actor;

    public function __construct($patient, $doctor, $medication, $actor)
    {
        $this->patient = $patient;
        $this->doctor = $doctor;
        $this->medication = $medication;
        $this->actor = $actor;
    }

    public function build()
    {
        return $this->subject('Medication Plan Added')
            ->view('parklane.emails.medication_plan_added');
    }
}
