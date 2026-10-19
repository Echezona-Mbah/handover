<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnitInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $doctor;
    public $invite;

    public function __construct($patient, $doctor, $invite)
    {
        $this->patient = $patient;
        $this->doctor = $doctor;
        $this->invite = $invite;
    }

    public function build()
    {
        return $this->subject('New Patient Unit Invite')
            ->view('parklane.emails.unit_invite');
    }
}
