<?php

namespace App\Notifications;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Notifications\Notification;

class LikeNotification extends Notification
{
    public function __construct(
        public User $liker,
        public Post $post
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'     => 'like',
            'user_id'  => $this->liker->id,
            'username' => $this->liker->username,
            'name'     => $this->liker->name,
            'avatar'   => $this->liker->avatar,
            'post_id'  => $this->post->id,
            'post_title' => $this->post->title ?? null,
        ];
    }
}
