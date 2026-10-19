<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NursingReportReview extends Model
{
    protected $fillable = [
        'nursing_report_id', 'reviewed_by', 'action', 'comment', 'snapshot',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    public function report()
    {
        return $this->belongsTo(NursingReport::class, 'nursing_report_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}

