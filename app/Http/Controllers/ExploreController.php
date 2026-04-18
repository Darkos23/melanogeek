<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ExploreController extends Controller
{
    public function index(Request $request): View
    {
        $query  = $request->input('q');
        $type   = $request->input('type');   // image | video | text
        $sort   = $request->input('sort', 'trending'); // trending | recent

        $posts = Post::with(['user'])
            ->where('is_published', true)
            ->whereHas('user', fn ($q) => $q
                ->where('is_active', true)
                ->where('is_private', false)
            )
            ->when($query, function ($q) use ($query) {
                $safe = '%'.Str::escapeLike($query).'%';
                $q->where(fn ($sub) =>
                    $sub->where('title', 'like', $safe)
                        ->orWhere('body', 'like', $safe)
                );
            })
            ->when($type, fn ($q) => $q->where('media_type', $type))
            ->when($sort === 'trending',
                fn ($q) => $q->orderByDesc('likes_count')->orderByDesc('comments_count'),
                fn ($q) => $q->latest()
            )
            ->paginate(24)
            ->withQueryString();

        return view('explore.index', compact('posts', 'query', 'type', 'sort'));
    }
}
