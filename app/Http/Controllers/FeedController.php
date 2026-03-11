<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Post;
use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class FeedController extends Controller
{
    /**
     * Construit la requête de posts du feed.
     * $followingIds est passé en paramètre pour éviter une double requête SQL.
     */
    private function feedQuery(User $user, bool $isDiscovery, Collection $followingIds)
    {
        if ($isDiscovery) {
            return Post::with(['user', 'mediaFiles'])
                ->where('is_published', true)
                ->whereHas('user', fn ($q) => $q
                    ->where('is_active', true)
                    ->where('is_private', false)
                );
        }

        return Post::with(['user', 'mediaFiles'])
            ->whereIn('user_id', $followingIds)
            ->where('is_published', true);
    }

    /**
     * IDs à masquer dans les suggestions (bloqués dans les deux sens).
     * Une seule requête au lieu de deux.
     */
    private function hiddenUserIds(User $user): array
    {
        return Block::where('blocker_id', $user->id)
            ->orWhere('blocked_id', $user->id)
            ->get(['blocker_id', 'blocked_id'])
            ->flatMap(fn ($b) => [(int) $b->blocker_id, (int) $b->blocked_id])
            ->filter(fn ($id) => $id !== $user->id)
            ->unique()
            ->values()
            ->toArray();
    }

    public function index(): View
    {
        $user         = auth()->user();
        $followingIds = $user->following()->pluck('users.id');  // 1 seule requête
        $isDiscovery  = $followingIds->isEmpty();

        $posts = $this->feedQuery($user, $isDiscovery, $followingIds)->latest()->paginate(10);

        // IDs likés & suivis → tableaux PHP pour les partials (0 requête N+1)
        $likedPostIds    = $user->likes()->pluck('post_id')->toArray();
        $followingIdsArr = $followingIds->map(fn ($id) => (int) $id)->toArray();

        // Suggestions : 1 seule requête Block (les deux directions en même temps)
        $hiddenIds   = $this->hiddenUserIds($user);
        $suggestions = User::where('id', '!=', $user->id)
            ->where('role', 'creator')
            ->where('is_active', true)
            ->where('is_private', false)
            ->whereNotIn('id', $followingIds)
            ->whereNotIn('id', $hiddenIds)
            ->withCount('followers')
            ->orderByDesc('followers_count')
            ->limit(6)
            ->get();

        // ── Stories ──────────────────────────────────────────────────
        $storyUserIds = $followingIds->push($user->id)->unique();

        $storiesRaw = Story::with('user')
            ->whereIn('user_id', $storyUserIds)
            ->where('expires_at', '>', now())
            ->latest()
            ->get()
            ->groupBy('user_id')
            ->map(fn ($group) => $group->first())
            ->values();

        $myActiveStory = Story::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->first();

        return view('feed.index', compact(
            'posts', 'isDiscovery', 'likedPostIds', 'followingIdsArr',
            'suggestions', 'storiesRaw', 'myActiveStory'
        ));
    }

    /**
     * Endpoint AJAX — retourne les posts de la page suivante en HTML.
     * GET /feed/more?page=2
     */
    public function more(Request $request)
    {
        $user         = auth()->user();
        $followingIds = $user->following()->pluck('users.id');  // 1 seule requête
        $isDiscovery  = $followingIds->isEmpty();

        $posts = $this->feedQuery($user, $isDiscovery, $followingIds)
            ->latest()
            ->paginate(10);

        $likedPostIds    = $user->likes()->pluck('post_id')->toArray();
        $followingIdsArr = $followingIds->map(fn ($id) => (int) $id)->toArray();

        $html = '';
        foreach ($posts as $post) {
            $html .= view('feed._post', [
                'post'           => $post,
                'likedPostIds'   => $likedPostIds,
                'followingIdsArr' => $followingIdsArr,
            ])->render();
        }

        return response()->json([
            'html'     => $html,
            'hasMore'  => $posts->hasMorePages(),
            'nextPage' => $posts->currentPage() + 1,
        ]);
    }
}
