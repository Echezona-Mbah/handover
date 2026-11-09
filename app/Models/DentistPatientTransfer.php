<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DentistPatientTransfer extends Model
{
    protected $table = 'dentist_patient_transfers';

    protected $fillable = [
        'patient_id',
        'hospital_id',
        'from_department_id',
        'from_unit_id',
        'to_department_id',
        'to_unit_id',
        'requested_by_dentist_id',
        'responded_by_dentist_id',
        'status',
        'notes',
        'responded_at',
    ];

    protected $casts = ['responded_at' => 'datetime'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
