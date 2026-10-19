<?php

namespace App\Mail;

use App\Models\Doctor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorReassignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $doctor;
    public $oldDeptName;
    public $newDeptName;
    public $oldUnitName;
    public $newUnitName;
    public $oldSubUnitName;
    public $newSubUnitName;

    public function __construct(
        Doctor $doctor,
        string $oldDeptName,
        string $newDeptName,
        string $oldUnitName,
        string $newUnitName,
        string $oldSubUnitName,
        string $newSubUnitName
    ) {
        $this->doctor = $doctor;
        $this->oldDeptName = $oldDeptName;
        $this->newDeptName = $newDeptName;
        $this->oldUnitName = $oldUnitName;
        $this->newUnitName = $newUnitName;
        $this->oldSubUnitName = $oldSubUnitName;
        $this->newSubUnitName = $newSubUnitName;
    }

    public function build()
    {
        return $this->subject('Your department/unit assignment has changed')
            ->view('parklane.emails.doctor_reassigned');
    }
}