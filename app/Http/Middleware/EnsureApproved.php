<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Admins, owners et fans passent toujours
        if ($user->isAdmin() || $user->isOwner() || $user->role === 'fan') {
            return $next($request);
        }

        // Candidature refusée
        if ($user->isRejected()) {
            return redirect()->route('application.rejected');
        }

        // Candidature en attente
        if ($user->isPending()) {
            return redirect()->route('application.pending');
        }

        return $next($request);
    }
}
