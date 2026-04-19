<?php
// Webhook de déploiement — token check désactivé temporairement

define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$output = [];

// Marquer les migrations déjà appliquées manuellement
$preApplied = [
    'site_visits'  => ['migration' => '2026_04_17_000003_create_site_visits_table',       'table' => 'site_visits',  'column' => null],
    'views_count'  => ['migration' => '2026_04_17_000002_add_views_count_to_posts_table',  'table' => 'posts',        'column' => 'views_count'],
];
foreach ($preApplied as $key => $m) {
    try {
        $exists = $m['column']
            ? \Schema::hasColumn($m['table'], $m['column'])
            : \Schema::hasTable($m['table']);
        if ($exists) {
            \DB::table('migrations')->updateOrInsert(
                ['migration' => $m['migration']],
                ['batch' => 1]
            );
            $output[] = '✓ Migration ' . $key . ' marquée comme exécutée.';
        }
    } catch (\Throwable $e) {
        $output[] = 'Migration check (' . $key . '): ' . $e->getMessage();
    }
}

$artisan = escapeshellarg(dirname(__DIR__) . '/artisan');

// Migrations
exec("php {$artisan} migrate --force 2>&1", $output);

// Package discovery (remplace post-autoload-dump Composer)
exec("php {$artisan} package:discover --ansi 2>&1", $output);

// Symlink storage (public/storage → storage/app/public) — exec() peut être désactivé
try {
    $target = dirname(__DIR__) . '/storage/app/public';
    $link   = __DIR__ . '/storage';
    if (! file_exists($link) && ! is_link($link)) {
        if (function_exists('symlink')) {
            symlink($target, $link);
            $output[] = '✓ Symlink storage créé.';
        } else {
            $output[] = '⚠ symlink() indisponible — créer le lien manuellement.';
        }
    } else {
        $output[] = '✓ Symlink storage déjà présent.';
    }
} catch (\Throwable $e) {
    $output[] = 'Storage link: ' . $e->getMessage();
}

// Artisan caches
exec("php {$artisan} config:clear 2>&1", $output);
exec("php {$artisan} view:clear 2>&1", $output);
exec("php {$artisan} route:clear 2>&1", $output);
exec("php {$artisan} config:cache 2>&1", $output);
exec("php {$artisan} route:cache 2>&1", $output);
exec("php {$artisan} view:cache 2>&1", $output);

echo '<pre style="background:#111;color:#8f8;padding:20px;font-family:monospace">' . htmlspecialchars(implode("\n", $output)) . '</pre>';
