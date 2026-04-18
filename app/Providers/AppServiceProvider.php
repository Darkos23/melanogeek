<?php

namespace App\Providers;

use App\Listeners\SendWebPushOnNotification;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
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

        // Marquer la migration views_count comme exécutée si la colonne existe déjà
        // (évite l'erreur "column already exists" lors du déploiement)
        try {
            if (Schema::hasColumn('posts', 'views_count')) {
                DB::table('migrations')->updateOrInsert(
                    ['migration' => '2026_04_17_000002_add_views_count_to_posts_table'],
                    ['batch' => 1]
                );
            }
        } catch (\Throwable $e) {
            // Silencieux — ne pas casser l'app si la DB n'est pas disponible
        }
    }
}
