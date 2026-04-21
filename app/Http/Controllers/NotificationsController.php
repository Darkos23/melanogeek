<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotificationsController extends Controller
{
    // ── Liste des notifications ─────────────────────────────────────
    public function index()
    {
        $me = auth()->user();

        $notifications = $me->notifications()
            ->latest()
            ->paginate(30);

        // Marquer toutes comme lues après affichage
        $me->unreadNotifications()->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    // ── Marquer une notification comme lue (via AJAX) ───────────────
    public function markRead(Request $request, string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['ok' => true]);
    }

    // ── Marquer toutes comme lues (via AJAX) ────────────────────────
    public function markAllRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    // ── Compte non-lues (polling nav) ───────────────────────────────
    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }

    // ── Données pour le dropdown nav (8 dernières) ───────────────────
    public function dropdown()
    {
        $me            = auth()->user();
        $notifications = $me->notifications()->latest()->take(8)->get();
        $unread        = $me->unreadNotifications()->count();

        return response()->json([
            'unread' => $unread,
            'items'  => $notifications->map(function ($n) {
                $avatar = $n->data['avatar'] ?? null;

                return [
                    'id'         => $n->id,
                    'type'       => $n->data['type']     ?? 'unknown',
                    'name'       => $n->data['name']     ?? null,
                    'username'   => $n->data['username'] ?? null,
                    'avatar'     => $avatar ? Storage::url($avatar) : null,
                    'post_id'    => $n->data['post_id']  ?? null,
                    'post_title' => $n->data['post_title'] ?? null,
                    'comment_body' => $n->data['comment_body'] ?? null,
                    'read_at'    => $n->read_at,
                    'ago'        => $n->created_at->diffForHumans(),
                ];
            }),
        ]);
    }
}
