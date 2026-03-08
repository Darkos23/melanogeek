<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request, Post $post): JsonResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:120'],
        ]);

        $already = Report::where('reporter_id', auth()->id())
                          ->where('post_id', $post->id)
                          ->exists();

        if ($already) {
            return response()->json(['message' => 'Déjà signalé.'], 409);
        }

        Report::create([
            'reporter_id' => auth()->id(),
            'post_id'     => $post->id,
            'reason'      => $request->reason,
        ]);

        return response()->json(['message' => 'Signalement envoyé.']);
    }
}
