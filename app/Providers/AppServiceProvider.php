<?php

namespace App\Providers;

use App\Listeners\SendWebPushOnNotification;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Envoyer un push web chaque fois qu'une notification database est émise
        Event::listen(NotificationSent::class, SendWebPushOnNotification::class);
    }
}
