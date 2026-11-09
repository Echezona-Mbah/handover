<?php
namespace App\Notifications;
use App\Models\NursingQuery;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
class NursingQueryReceived extends Notification { use Queueable; public function __construct(private NursingQuery $query) {} public function via(object $notifiable): array { return ['database']; } public function toDatabase(object $notifiable): array { return ['title' => 'New nursing query', 'query_id' => $this->query->id]; } }
