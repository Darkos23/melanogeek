<?php
// Webhook de déploiement — token check désactivé temporairement

define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$output = [];

// Marquer la migration views_count comme déjà exécutée si colonne existe
try {
    if (\Schema::hasColumn('posts', 'views_count')) {
        \DB::table('migrations')->updateOrInsert(
            ['migration' => '2026_04_17_000002_add_views_count_to_posts_table'],
            ['batch' => 1]
        );
        $output[] = '✓ Migration views_count marquée comme exécutée.';
    }
} catch (\Throwable $e) {
    $output[] = 'Migration check: ' . $e->getMessage();
}

$artisan = escapeshellarg(dirname(__DIR__) . '/artisan');

// Migrations
exec("php {$artisan} migrate --force 2>&1", $output);

// Package discovery (remplace post-autoload-dump Composer)
exec("php {$artisan} package:discover --ansi 2>&1", $output);

// Symlink storage (public/storage → storage/app/public)
exec("php {$artisan} storage:link 2>&1", $output);

// Artisan caches
exec("php {$artisan} config:clear 2>&1", $output);
exec("php {$artisan} view:clear 2>&1", $output);
exec("php {$artisan} route:clear 2>&1", $output);
exec("php {$artisan} config:cache 2>&1", $output);
exec("php {$artisan} route:cache 2>&1", $output);
exec("php {$artisan} view:cache 2>&1", $output);

echo '<pre style="background:#111;color:#8f8;padding:20px;font-family:monospace">' . htmlspecialchars(implode("\n", $output)) . '</pre>';
