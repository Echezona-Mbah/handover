<?php
namespace App\Notifications;
use App\Models\NursingEscalation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
class NursingEscalated extends Notification { use Queueable; public function __construct(private NursingEscalation $escalation) {} public function via(object $notifiable): array { return ['database']; } public function toDatabase(object $notifiable): array { return ['title' => 'Nursing report escalated', 'escalation_id' => $this->escalation->id, 'report_id' => $this->escalation->nursing_report_id]; } }
