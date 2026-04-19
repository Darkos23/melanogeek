<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public Post $post, public string $reason = '') {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'post_rejected',
            'post_id'    => $this->post->id,
            'post_title' => $this->post->title ?? '(sans titre)',
            'reason'     => $this->reason,
            'message'    => "Ton article « {$this->post->title} » n'a pas été retenu." . ($this->reason ? " Raison : {$this->reason}" : ''),
        ];
    }
}
