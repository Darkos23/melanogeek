<?php
// Webhook de déploiement — protégé par token
// Token check désactivé temporairement

$output = [];

// Pull depuis GitHub
exec('cd ' . escapeshellarg(dirname(__DIR__)) . ' && git reset --hard origin/main 2>&1', $output);
exec('cd ' . escapeshellarg(dirname(__DIR__)) . ' && git pull origin main 2>&1', $output);

// Composer (sans scripts pour éviter les timeouts)
exec('cd ' . escapeshellarg(dirname(__DIR__)) . ' && php composer.phar install --no-dev --optimize-autoloader --no-interaction 2>&1', $output);

// Artisan
exec('php ' . escapeshellarg(dirname(__DIR__) . '/artisan') . ' migrate --force 2>&1', $output);
exec('php ' . escapeshellarg(dirname(__DIR__) . '/artisan') . ' config:clear 2>&1', $output);
exec('php ' . escapeshellarg(dirname(__DIR__) . '/artisan') . ' view:clear 2>&1', $output);
exec('php ' . escapeshellarg(dirname(__DIR__) . '/artisan') . ' route:clear 2>&1', $output);

// Recréer les caches pour la prod
exec('php ' . escapeshellarg(dirname(__DIR__) . '/artisan') . ' config:cache 2>&1', $output);
exec('php ' . escapeshellarg(dirname(__DIR__) . '/artisan') . ' route:cache 2>&1', $output);
exec('php ' . escapeshellarg(dirname(__DIR__) . '/artisan') . ' view:cache 2>&1', $output);

echo '<pre>' . htmlspecialchars(implode("\n", $output)) . '</pre>';
