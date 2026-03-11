<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CMMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isCM()) {
            abort(403, 'Accès réservé au Community Manager.');
        }

        return $next($request);
    }
}
