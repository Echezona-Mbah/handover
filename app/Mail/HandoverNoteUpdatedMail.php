<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HandoverNoteUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $doctor;
    public $handover;
    public $actor;

    public function __construct($patient, $doctor, $handover, $actor)
    {
        $this->patient = $patient;
        $this->doctor = $doctor;
        $this->handover = $handover;
        $this->actor = $actor;
    }

    public function build()
    {
        return $this->subject('Handover Note Updated')
            ->view('parklane.emails.handover_note_updated');
    }
}
