<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Notifications\Notification;

class NewPostNotification extends Notification
{
    public function __construct(
        public User $author,
        public Post $post
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'new_post',
            'user_id'    => $this->author->id,
            'username'   => $this->author->username,
            'name'       => $this->author->name,
            'avatar'     => $this->author->avatar,
            'post_id'    => $this->post->id,
            'post_title' => $this->post->title ?? null,
        ];
    }
}
