<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class FollowNotification extends Notification
{
    public function __construct(
        public User $follower
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'     => 'follow',
            'user_id'  => $this->follower->id,
            'username' => $this->follower->username,
            'name'     => $this->follower->name,
            'avatar'   => $this->follower->avatar,
        ];
    }
}
