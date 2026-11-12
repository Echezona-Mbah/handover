<?php
namespace App\Notifications;

use App\Models\Nurses;
use App\Models\NursingQuery;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NursingQueryReceived extends Notification
{
    use Queueable;

    public function __construct(private NursingQuery $query) {}

    public function via(object $notifiable): array
    {
        return filled($notifiable->email ?? null) ? ['database', 'mail'] : ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New nursing query',
            'message' => \Illuminate\Support\Str::limit($this->query->message, 240),
            'sender_name' => $this->senderName(),
            'ward_name' => $this->query->ward?->name ?? 'Unassigned ward',
            'query_id' => $this->query->id,
            'url' => $notifiable instanceof Nurses ? route('nurse.queries.index') : route('nursing.queries.index'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $notifiable instanceof Nurses ? $notifiable->full_name : $notifiable->name;
        $url = $notifiable instanceof Nurses ? route('nurse.queries.index') : route('nursing.queries.index');

        return (new MailMessage)
            ->subject('New nursing query')
            ->greeting('Hello '.$name.',')
            ->line('From: '.$this->senderName())
            ->line('Ward: '.($this->query->ward?->name ?? 'Unassigned ward'))
            ->line('Query: '.\Illuminate\Support\Str::limit($this->query->message, 1000))
            ->action('View nursing queries', $url);
    }

    private function senderName(): string
    {
        $sender = $this->query->sender_type === 'nurse'
            ? Nurses::find($this->query->sender_id)
            : User::find($this->query->sender_id);

        return $sender instanceof Nurses ? $sender->full_name : ($sender?->name ?? 'Nursing staff');
    }
}
