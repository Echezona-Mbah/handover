<?php

namespace App\Mail;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\PatientUnitInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnitInviteSentMail extends Mailable
{
    use Queueable, SerializesModels;

    public Patient $patient;
    public Doctor $inviter;
    public PatientUnitInvite $invite;
    public string $invitedUnitName;

    public function __construct(Patient $patient, Doctor $inviter, PatientUnitInvite $invite, string $invitedUnitName)
    {
        $this->patient = $patient;
        $this->inviter = $inviter;
        $this->invite = $invite;
        $this->invitedUnitName = $invitedUnitName;
    }

    public function build()
    {
        $html = "
            <p>Hello Dr. {$this->inviter->full_name},</p>
            <p>Your invite for <strong>{$this->invitedUnitName}</strong> to help manage patient
            <strong>{$this->patient->full_name}</strong> has been sent.</p>
            <p><strong>Instructions given:</strong><br>" . nl2br(e($this->invite->instructions)) . "</p>
            <p>The unit must accept the invite before they can make changes to this patient's records.
            You'll be notified by email as soon as they respond.</p>
        ";

        return $this->subject('Unit Invite Sent — ' . $this->patient->full_name)
            ->html($html);
    }
}