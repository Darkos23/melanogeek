<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        // CORS pour l'API (autoriser Nuxt.js frontend)
        $middleware->prepend(\Illuminate\Http\Middleware\HandleCors::class);

        // Headers de sécurité HTTP sur toutes les réponses
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);

        // Compteur de visites (1 incrément par session, web uniquement)
        $middleware->appendToGroup('web', \App\Http\Middleware\TrackSiteVisit::class);

        $middleware->alias([
            'owner'    => \App\Http\Middleware\SuperAdminMiddleware::class,
            'admin'    => \App\Http\Middleware\AdminMiddleware::class,
            'cm'       => \App\Http\Middleware\CMMiddleware::class,
            'approved' => \App\Http\Middleware\EnsureApproved::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
