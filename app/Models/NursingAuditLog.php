<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class NursingAuditLog extends Model { protected $fillable = ['hospital_id','actor_type','actor_id','action','auditable_type','auditable_id','metadata']; protected $casts = ['metadata' => 'array']; }
