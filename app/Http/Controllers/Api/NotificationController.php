<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // ── Liste des notifications ────────────────────────────────────
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $notifications->map(fn($n) => [
                'id'          => $n->id,
                'type'        => class_basename($n->type),
                'data'        => $n->data,
                'read_at'     => $n->read_at?->toIso8601String(),
                'created_at'  => $n->created_at->toIso8601String(),
            ]),
            'meta' => [
                'current_page'  => $notifications->currentPage(),
                'last_page'     => $notifications->lastPage(),
                'total'         => $notifications->total(),
                'unread_count'  => $request->user()->unreadNotifications()->count(),
            ],
        ]);
    }

    // ── Marquer toutes comme lues ─────────────────────────────────
    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);
        return response()->json(['message' => 'Toutes les notifications marquées comme lues.']);
    }

    // ── Marquer une notification comme lue ────────────────────────
    public function markRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['message' => 'Notification lue.']);
    }

    // ── Nombre de non-lues ────────────────────────────────────────
    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'count' => $request->user()->unreadNotifications()->count(),
        ]);
    }
}
