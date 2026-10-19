<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DischargeTransferMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $doctor;
    public $notes;
    public $actor;
    public $targetUnitName;
    public $targetSubUnitName;
    public $direction;
    public $sourceUnitName;
    public $sourceSubUnitName;

    public function __construct(
        $patient,
        $doctor,
        $notes = null,
        $actor = null,
        $targetUnitName = null,
        $targetSubUnitName = null,
        $direction = 'in',
        $sourceUnitName = null,
        $sourceSubUnitName = null
    ) {
        $this->patient = $patient;
        $this->doctor = $doctor;
        $this->notes = $notes;
        $this->actor = $actor;
        $this->targetUnitName = $targetUnitName;
        $this->targetSubUnitName = $targetSubUnitName;
        $this->direction = $direction;
        $this->sourceUnitName = $sourceUnitName;
        $this->sourceSubUnitName = $sourceSubUnitName;
    }

    public function build()
    {
        return $this->subject('Patient Transfer Notification')
            ->view('parklane.emails.discharge_transfer');
    }
}
