<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    /** Save or update a push subscription for the authenticated user. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'endpoint'  => 'required|string|url',
            'publicKey' => 'required|string',
            'authToken' => 'required|string',
        ]);

        auth()->user()->pushSubscriptions()->updateOrCreate(
            ['endpoint' => $validated['endpoint']],
            [
                'public_key' => $validated['publicKey'],
                'auth_token' => $validated['authToken'],
            ]
        );

        return response()->json(['ok' => true]);
    }

    /** Remove a push subscription. */
    public function destroy(Request $request)
    {
        $request->validate(['endpoint' => 'required|string']);

        auth()->user()->pushSubscriptions()
            ->where('endpoint', $request->endpoint)
            ->delete();

        return response()->json(['ok' => true]);
    }
}
