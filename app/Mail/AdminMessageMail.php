<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $doctor;
    public $messageText;

    public function __construct($doctor, $messageText)
    {
        $this->doctor = $doctor;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject('Admin Message')
            ->view('parklane.emails.admin_message');
    }
}
