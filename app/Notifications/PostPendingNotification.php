<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostPendingNotification extends Notification
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
            'type'        => 'post_pending',
            'post_id'     => $this->post->id,
            'post_title'  => $this->post->title ?? '(sans titre)',
            'author_id'   => $this->post->user_id,
            'author_name' => $this->post->user->name,
            'author_username' => $this->post->user->username,
            'message'     => "{$this->post->user->name} a soumis un article en attente de validation.",
        ];
    }
}
