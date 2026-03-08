<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // ── Liste des conversations ────────────────────────────────────
    public function conversations(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        // Récupère tous les messages impliquant l'utilisateur
        $messages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->latest()
            ->get();

        // Groupe par interlocuteur
        $conversations = $messages
            ->groupBy(function ($msg) use ($userId) {
                return $msg->sender_id === $userId
                    ? $msg->receiver_id
                    : $msg->sender_id;
            })
            ->map(function ($msgs, $partnerId) use ($userId) {
                $partner     = User::find($partnerId);
                $lastMessage = $msgs->first(); // déjà trié par latest
                $unread      = $msgs->filter(
                    fn($m) => $m->receiver_id === $userId && is_null($m->read_at)
                )->count();

                if (! $partner) return null;

                return [
                    'user' => [
                        'id'          => $partner->id,
                        'name'        => $partner->name,
                        'username'    => $partner->username,
                        'avatar'      => $partner->avatar,
                        'is_verified' => (bool) $partner->is_verified,
                    ],
                    'last_message' => [
                        'body'       => $lastMessage->body,
                        'created_at' => $lastMessage->created_at->toIso8601String(),
                        'is_mine'    => $lastMessage->sender_id === $userId,
                    ],
                    'unread_count' => $unread,
                ];
            })
            ->filter()          // retire les null (partenaire supprimé)
            ->sortByDesc(fn($c) => $c['last_message']['created_at'])
            ->values();

        return response()->json(['data' => $conversations]);
    }

    // ── Messages avec un utilisateur ──────────────────────────────
    public function show(Request $request, string $username): JsonResponse
    {
        $me      = $request->user();
        $partner = User::where('username', $username)->firstOrFail();

        // Marquer comme lus les messages reçus depuis ce partenaire
        Message::where('sender_id', $partner->id)
            ->where('receiver_id', $me->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::where(function ($q) use ($me, $partner) {
                $q->where('sender_id', $me->id)
                  ->where('receiver_id', $partner->id);
            })
            ->orWhere(function ($q) use ($me, $partner) {
                $q->where('sender_id', $partner->id)
                  ->where('receiver_id', $me->id);
            })
            ->oldest()
            ->paginate(50);

        return response()->json([
            'data' => $messages->map(fn($m) => [
                'id'         => $m->id,
                'body'       => $m->body,
                'is_mine'    => $m->sender_id === $me->id,
                'read_at'    => $m->read_at?->toIso8601String(),
                'created_at' => $m->created_at->toIso8601String(),
            ]),
            'partner' => [
                'id'          => $partner->id,
                'name'        => $partner->name,
                'username'    => $partner->username,
                'avatar'      => $partner->avatar,
                'is_verified' => (bool) $partner->is_verified,
            ],
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page'    => $messages->lastPage(),
                'total'        => $messages->total(),
            ],
        ]);
    }

    // ── Envoyer un message ────────────────────────────────────────
    public function store(Request $request, string $username): JsonResponse
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $partner = User::where('username', $username)->firstOrFail();

        // Impossible de s'envoyer un message à soi-même
        abort_if($partner->id === $request->user()->id, 422, 'Vous ne pouvez pas vous envoyer un message.');

        $message = Message::create([
            'sender_id'   => $request->user()->id,
            'receiver_id' => $partner->id,
            'body'        => $request->body,
        ]);

        return response()->json([
            'id'         => $message->id,
            'body'       => $message->body,
            'is_mine'    => true,
            'read_at'    => null,
            'created_at' => $message->created_at->toIso8601String(),
        ], 201);
    }

    // ── Supprimer un message (seulement l'expéditeur) ─────────────
    public function destroy(Request $request, Message $message): JsonResponse
    {
        abort_if($message->sender_id !== $request->user()->id, 403, 'Non autorisé.');

        $message->delete();

        return response()->json(['message' => 'Message supprimé.']);
    }

    // ── Nombre de messages non lus ────────────────────────────────
    public function unreadCount(Request $request): JsonResponse
    {
        $count = Message::where('receiver_id', $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
}
