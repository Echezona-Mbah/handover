<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferDecisionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $doctor;
    public $decision;
    public $actor;
    public $oldUnitName;
    public $oldSubUnitName;
    public $newUnitName;
    public $newSubUnitName;

    public function __construct($patient, $doctor, $decision, $actor, $oldUnitName, $oldSubUnitName, $newUnitName, $newSubUnitName)
    {
        $this->patient = $patient;
        $this->doctor = $doctor;
        $this->decision = $decision;
        $this->actor = $actor;
        $this->oldUnitName = $oldUnitName;
        $this->oldSubUnitName = $oldSubUnitName;
        $this->newUnitName = $newUnitName;
        $this->newSubUnitName = $newSubUnitName;
    }

    public function build()
    {
        return $this->subject('Patient Transfer ' . ucfirst($this->decision))
            ->view('parklane.emails.transfer_decision');
    }
}
