<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class NursingEscalation extends Model { protected $fillable = ['nursing_report_id','nursing_query_id','escalated_by_type','escalated_by_id','reason','resolved_at']; protected $casts = ['resolved_at' => 'datetime']; }
