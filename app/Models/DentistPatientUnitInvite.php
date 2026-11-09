<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DentistPatientUnitInvite extends Model
{
    protected $table = 'dentist_patient_unit_invites';

    protected $fillable = [
        'patient_id',
        'hospital_id',
        'dentist_department_id',
        'dentist_unit_id',
        'dentist_id',
        'instructions',
        'status',
        'responded_by_dentist_id',
        'responded_at',
    ];

    protected $casts = ['responded_at' => 'datetime'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
