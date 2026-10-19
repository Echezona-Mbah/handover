<?php

namespace App\Mail;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\PatientUnitInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnitInviteDecisionMail extends Mailable
{
    use Queueable, SerializesModels;

    public Patient $patient;
    public Doctor $recipient;
    public Doctor $respondingDoctor;
    public PatientUnitInvite $invite;
    public string $decision; // 'accepted' or 'rejected'

    public function __construct(Patient $patient, Doctor $recipient, Doctor $respondingDoctor, PatientUnitInvite $invite, string $decision)
    {
        $this->patient = $patient;
        $this->recipient = $recipient;
        $this->respondingDoctor = $respondingDoctor;
        $this->invite = $invite;
        $this->decision = $decision;
    }

    public function build()
    {
        $verb = $this->decision === 'accepted' ? 'accepted' : 'rejected';
        $respondingCadre = $this->respondingDoctor->cadre
            ? ucwords(str_replace('_', ' ', $this->respondingDoctor->cadre))
            : 'Doctor';

        $html = "
            <p>Hello Dr. {$this->recipient->full_name},</p>
            <p>{$respondingCadre} {$this->respondingDoctor->full_name} has <strong>{$verb}</strong>
            the invite to manage patient <strong>{$this->patient->full_name}</strong>.</p>
        ";

        if ($this->decision === 'accepted') {
            $html .= "<p>Their unit can now view and update this patient's records.</p>";
        } else {
            $html .= "<p>Their unit will not be managing this patient's care.</p>";
        }

        return $this->subject('Unit Invite ' . ucfirst($verb) . ' — ' . $this->patient->full_name)
            ->html($html);
    }
}