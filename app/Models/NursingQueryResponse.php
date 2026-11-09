<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class NursingQueryResponse extends Model { protected $fillable = ['nursing_query_id','responder_type','responder_id','message']; public function query() { return $this->belongsTo(NursingQuery::class, 'nursing_query_id'); } }
