<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class NursingQuery extends Model {
    protected $fillable = ['hospital_id','ward_id','nursing_report_id','sender_type','sender_id','recipient_type','recipient_id','message','status','closed_at'];
    protected $casts = ['closed_at' => 'datetime'];
    public function responses() { return $this->hasMany(NursingQueryResponse::class); }
    public function report() { return $this->belongsTo(NursingReport::class, 'nursing_report_id'); }
    public function ward() { return $this->belongsTo(Ward::class); }
}
