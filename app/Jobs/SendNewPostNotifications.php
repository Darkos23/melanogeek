<?php

namespace App\Jobs;

use App\Models\Post;
use App\Notifications\NewPostNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendNewPostNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Post $post) {}

    public function handle(): void
    {
        $author = $this->post->user;

        if (! $author) {
            return;
        }

        // Récupérer tous les abonnés de l'auteur
        $followers = $author->followers()->get();

        if ($followers->isEmpty()) {
            return;
        }

        Notification::sendNow($followers, new NewPostNotification($author, $this->post));
    }
}
