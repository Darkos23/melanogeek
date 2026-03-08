<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\User;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function toggle(Request $request, User $user)
    {
        $me = $request->user();

        abort_if($me->id === $user->id, 422, 'Tu ne peux pas te bloquer toi-même.');

        $existing = Block::where('blocker_id', $me->id)->where('blocked_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
            $blocked = false;
        } else {
            Block::create(['blocker_id' => $me->id, 'blocked_id' => $user->id]);
            $blocked = true;

            // Si on bloque quelqu'un, on se désabonne automatiquement
            $me->following()->detach($user->id);
            $user->following()->detach($me->id);
        }

        if ($request->expectsJson()) {
            return response()->json(['blocked' => $blocked]);
        }

        $msg = $blocked
            ? "{$user->name} a été bloqué. Tu ne verras plus son contenu."
            : "{$user->name} a été débloqué.";

        return back()->with('success', $msg);
    }
}
