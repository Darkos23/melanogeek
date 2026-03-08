<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Bascule la disponibilité du créateur connecté.
     * POST /availability/toggle
     */
    public function toggle(Request $request)
    {
        $user = $request->user();

        abort_if(! $user->isCreator(), 403);

        $user->update(['is_available' => ! $user->is_available]);

        $message = $user->is_available
            ? 'Tu es maintenant disponible pour des commandes ! 🟢'
            : 'Tu as indiqué être indisponible pour de nouvelles commandes. 🔴';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'available' => $user->is_available,
                'message'   => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
