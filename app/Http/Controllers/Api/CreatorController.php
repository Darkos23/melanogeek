<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\CreatorRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    // ── Liste des créateurs ───────────────────────────────────────
    public function index(Request $request): JsonResponse
    {
        $niche = $request->get('niche');

        $creators = User::where('role', 'creator')
            ->whereNotIn('role', ['admin', 'owner'])  // sécurité supplémentaire
            ->where('is_active', true)
            ->when($niche, fn($q) => $q->where('niche', $niche))
            ->withCount('followers')
            ->orderByDesc('followers_count')
            ->paginate(20);

        return response()->json([
            'data' => UserResource::collection($creators),
            'meta' => [
                'current_page' => $creators->currentPage(),
                'last_page'    => $creators->lastPage(),
                'total'        => $creators->total(),
            ],
        ]);
    }

    // ── Demande de statut créateur ────────────────────────────────
    public function request(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isCreator()) {
            return response()->json(['message' => 'Vous êtes déjà créateur.'], 422);
        }

        if ($user->creatorRequest?->isPending()) {
            return response()->json(['message' => 'Votre demande est en cours de traitement.'], 422);
        }

        $request->validate([
            'motivation' => ['required', 'string', 'min:50', 'max:1000'],
        ]);

        CreatorRequest::updateOrCreate(
            ['user_id' => $user->id],
            ['motivation' => $request->motivation, 'status' => 'pending', 'reviewed_by' => null, 'reviewed_at' => null]
        );

        return response()->json(['message' => 'Demande envoyée avec succès. Nous vous répondrons sous 48h.'], 201);
    }
}
