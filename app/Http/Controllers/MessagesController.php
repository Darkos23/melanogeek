<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    // ── Liste des conversations (redirige vers la 1ère ou affiche vide) ──
    public function index()
    {
        $me = auth()->user();

        $partner = Message::where('sender_id', $me->id)
            ->orWhere('receiver_id', $me->id)
            ->latest()
            ->first();

        if ($partner) {
            $otherId = $partner->sender_id === $me->id
                ? $partner->receiver_id
                : $partner->sender_id;
            $other = User::find($otherId);
            if ($other) {
                return redirect()->route('messages.show', $other->username);
            }
        }

        // Aucune conversation : afficher la vue vide
        return view('messages.show', [
            'conversations' => collect(),
            'partner'       => null,
            'messages'      => collect(),
        ]);
    }

    // ── Conversation avec un utilisateur ──────────────────────────────
    public function show(User $user)
    {
        $me = auth()->user();

        // Interdire si l'un a bloqué l'autre
        abort_if($me->isBlocking($user) || $user->isBlocking($me), 403, 'Conversation non disponible.');

        // Marquer les messages reçus comme lus
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Charger les messages du fil
        $messages = Message::where(function ($q) use ($me, $user) {
                $q->where('sender_id', $me->id)->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($me, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $me->id);
            })
            ->oldest()
            ->get();

        // Charger la liste des conversations (sidebar)
        $conversations = $this->getConversations($me);

        return view('messages.show', compact('conversations', 'messages', 'user'));
    }

    // ── Envoyer un message ────────────────────────────────────────────
    public function store(Request $request, User $user)
    {
        $request->validate(['body' => 'required|string|max:2000']);

        $me = auth()->user();

        abort_if($user->id === $me->id, 422);
        abort_if($me->isBlocking($user) || $user->isBlocking($me), 403);

        $message = Message::create([
            'sender_id'   => $me->id,
            'receiver_id' => $user->id,
            'body'        => $request->body,
        ]);

        return response()->json([
            'id'         => $message->id,
            'body'       => $message->body,
            'is_mine'    => true,
            'created_at' => $message->created_at->toIso8601String(),
        ], 201);
    }

    // ── Polling : nouveaux messages depuis un ID ──────────────────────
    public function poll(Request $request, User $user)
    {
        $me    = auth()->user();
        $after = (int) $request->query('after', 0);

        $messages = Message::where(function ($q) use ($me, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $me->id);
            })
            ->where('id', '>', $after)
            ->oldest()
            ->get()
            ->map(fn($m) => [
                'id'         => $m->id,
                'body'       => $m->body,
                'is_mine'    => false,
                'created_at' => $m->created_at->toIso8601String(),
            ]);

        return response()->json(['messages' => $messages]);
    }

    // ── Nombre de messages non-lus (badge nav) ───────────────────────
    public function unreadCount()
    {
        $count = Message::where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    // ── Recherche d'utilisateurs pour "Nouveau message" ──────────────
    public function searchUsers(Request $request)
    {
        $q    = trim($request->query('q', ''));
        $me   = auth()->id();

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $blockedIds = auth()->user()->blockedIds();
        $blockedByIds = \App\Models\Block::where('blocked_id', $me)->pluck('blocker_id')->toArray();

        $users = User::where('id', '!=', $me)
            ->where('role', '!=', 'owner')
            ->where('is_active', true)
            ->whereNotIn('id', $blockedIds)
            ->whereNotIn('id', $blockedByIds)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('username', 'like', "%{$q}%");
            })
            ->select('id', 'name', 'username', 'avatar')
            ->limit(8)
            ->get()
            ->map(fn($u) => [
                'id'       => $u->id,
                'name'     => $u->name,
                'username' => $u->username,
                'avatar'   => $u->avatar ? \Storage::url($u->avatar) : null,
            ]);

        return response()->json($users);
    }

    // ── Helper : liste des conversations ─────────────────────────────
    private function getConversations(User $me): \Illuminate\Support\Collection
    {
        // Eager-load sender & receiver pour éviter les N+1
        $allMessages = Message::where('sender_id', $me->id)
            ->orWhere('receiver_id', $me->id)
            ->with([
                'sender:id,name,username,avatar,is_verified',
                'receiver:id,name,username,avatar,is_verified',
            ])
            ->latest()
            ->get();

        return $allMessages
            ->groupBy(function ($msg) use ($me) {
                return $msg->sender_id === $me->id ? $msg->receiver_id : $msg->sender_id;
            })
            ->map(function ($msgs, $partnerId) use ($me) {
                $last    = $msgs->first();
                $partner = $last->sender_id === $me->id ? $last->receiver : $last->sender;
                if (! $partner) return null;

                $unread = $msgs->filter(
                    fn($m) => $m->receiver_id === $me->id && is_null($m->read_at)
                )->count();

                return (object) [
                    'partner'      => $partner,
                    'last_message' => $last,
                    'unread'       => $unread,
                ];
            })
            ->filter()
            ->sortByDesc(fn($c) => $c->last_message->created_at)
            ->values();
    }
}
