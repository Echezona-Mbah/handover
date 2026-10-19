<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmergencyPatientAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $doctor;
    public $actor;
    public $action;

    public function __construct($patient, $doctor, $actor, $action = 'added')
    {
        $this->patient = $patient;
        $this->doctor = $doctor;
        $this->actor = $actor;
        $this->action = $action;
    }

    public function build()
        {
            return $this->subject('Emergency Patient Alert')
                ->view('parklane.emails.emergency_patient_alert');
        }

}
