<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HandoverNoteAddedMail extends Mailable
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
        return $this->subject('New Handover Note Added')
            ->view('parklane.emails.handover_note_added');
    }
}
