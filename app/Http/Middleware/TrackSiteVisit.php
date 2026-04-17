<?php

namespace App\Http\Middleware;

use App\Models\SiteVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        // Compter une seule fois par session (pas à chaque rechargement)
        if (! $request->session()->has('site_visited')) {
            $request->session()->put('site_visited', true);
            // Ne pas compter bots / crawlers
            $ua = $request->userAgent() ?? '';
            if (! preg_match('/bot|crawl|slurp|spider|mediapartners/i', $ua)) {
                try {
                    SiteVisit::addVisit();
                } catch (\Throwable $e) {
                    // Table pas encore migrée — on ignore silencieusement
                }
            }
        }

        return $next($request);
    }
}
