<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    public function __construct(
        public User    $commenter,
        public Post    $post,
        public Comment $comment
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'comment',
            'user_id'      => $this->commenter->id,
            'username'     => $this->commenter->username,
            'name'         => $this->commenter->name,
            'avatar'       => $this->commenter->avatar,
            'post_id'      => $this->post->id,
            'post_title'   => $this->post->title ?? null,
            'comment_id'   => $this->comment->id,
            'comment_body' => \Str::limit($this->comment->body, 80),
        ];
    }
}
