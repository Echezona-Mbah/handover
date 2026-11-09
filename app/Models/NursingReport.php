<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NursingReport extends Model
{
    protected $fillable = [
        'hospital_id', 'ward_id', 'nurse_id', 'patient_id', 'report_type',
        'report_date', 'shift', 'summary', 'incidents', 'referrals',
        'administrative_notes', 'assigned_admin_id', 'target_admin_role', 'recipient_type', 'recipient_id', 'recipient_role', 'status', 'submitted_at', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'report_date' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function nurse()
    {
        return $this->belongsTo(Nurses::class, 'nurse_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }

    public function reviews()
    {
        return $this->hasMany(NursingReportReview::class);
    }

    public function queries() { return $this->hasMany(NursingQuery::class); }
}
