<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrossRolePatientRequest extends Model
{
    protected $fillable = [
        'patient_id',
        'hospital_id',
        'request_type',
        'source_role',
        'source_actor_id',
        'source_department_id',
        'source_unit_id',
        'source_sub_unit_id',
        'target_role',
        'target_department_id',
        'target_unit_id',
        'target_sub_unit_id',
        'message',
        'status',
        'responded_by_role',
        'responded_by_id',
        'responded_at',
    ];

    protected $casts = ['responded_at' => 'datetime'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
