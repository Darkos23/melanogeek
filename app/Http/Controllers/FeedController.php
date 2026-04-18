<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $me             = auth()->user();
        $followingIds    = $me->following()->pluck('users.id')->toArray();
        $followingIdsArr = $followingIds;

        // Posts des gens suivis + les siens, triés par date
        $posts = Post::with(['user', 'mediaFiles'])
            ->published()
            ->whereIn('user_id', array_merge($followingIds, [$me->id]))
            ->latest()
            ->paginate(12);

        // IDs des posts likés par l'utilisateur (pour afficher le cœur actif)
        $likedPostIds = Like::where('user_id', $me->id)
            ->whereIn('post_id', $posts->pluck('id'))
            ->pluck('post_id')
            ->toArray();

        return view('feed.index', compact(
            'posts',
            'likedPostIds',
            'followingIdsArr'
        ));
    }
}
