<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public Post $post) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'post_approved',
            'post_id'    => $this->post->id,
            'post_title' => $this->post->title ?? '(sans titre)',
            'message'    => "Ton article « {$this->post->title} » a été approuvé et publié ! 🎉",
        ];
    }
}
