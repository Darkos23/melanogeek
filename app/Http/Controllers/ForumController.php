<?php

namespace App\Http\Controllers;

use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ForumController extends Controller
{
    public function index(Request $request): View
    {
        $sort = $request->input('sort', '');
        $cat  = $request->input('cat');

        $threads = ForumThread::with(['user'])
            ->when($cat, fn($q) => $q->where('category', $cat))
            ->when($sort === 'popular',    fn($q) => $q->orderByDesc('replies_count')->orderByDesc('views_count'))
            ->when($sort === 'unanswered', fn($q) => $q->where('replies_count', 0)->latest())
            ->when($sort === 'recent' || !$sort, fn($q) => $q->orderByDesc('is_pinned')->orderByDesc('last_reply_at'))
            ->paginate(20)
            ->withQueryString();

        $categories = ForumThread::CATEGORIES;

        $stats = [
            'threads' => ForumThread::count(),
            'replies' => ForumReply::count(),
            'members' => User::where('is_active', true)->count(),
        ];

        $topContributors = User::withCount('forumThreads', 'forumReplies')
            ->having('forum_threads_count', '>', 0)
            ->orderByDesc('forum_threads_count')
            ->limit(4)
            ->get();

        return view('forum.index', compact('threads', 'categories', 'stats', 'topContributors', 'sort', 'cat'));
    }

    public function create(): View
    {
        $categories = ForumThread::CATEGORIES;
        return view('forum.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'    => ['required', 'string', 'min:5', 'max:200'],
            'body'     => ['required', 'string', 'min:10', 'max:80000'],
            'category' => ['required', 'string', Rule::in(array_keys(ForumThread::CATEGORIES))],
        ], [
            'title.required' => 'Le titre est obligatoire.',
            'title.min'      => 'Le titre doit faire au moins 5 caractères.',
            'body.required'  => 'Le contenu est obligatoire.',
            'body.min'       => 'Le contenu doit faire au moins 10 caractères.',
            'category.required' => 'Choisis une catégorie.',
        ]);

        $thread = $request->user()->forumThreads()->create([
            'title'         => $data['title'],
            'body'          => $data['body'],
            'category'      => $data['category'],
            'last_reply_at' => now(),
        ]);

        return redirect()->route('forum.show', $thread)->with('status', 'thread-created');
    }

    public function show(Request $request, ForumThread $thread): View
    {
        // Compter la vue — une fois par session, jamais pour l'auteur lui-même
        $sessionKey = 'viewed_thread_' . $thread->id;
        if (! session()->has($sessionKey) && auth()->id() !== $thread->user_id) {
            try { $thread->increment('views_count'); } catch (\Throwable $e) {}
            session()->put($sessionKey, true);
        }
        $thread->load('user');

        $replies = $thread->replies()
            ->with('user')
            ->oldest()
            ->paginate(20)
            ->withQueryString();

        return view('forum.show', compact('thread', 'replies'));
    }

    public function reply(Request $request, ForumThread $thread): RedirectResponse
    {
        $request->validate([
            'body' => ['required', 'string', 'min:1', 'max:5000'],
        ]);

        $thread->replies()->create([
            'user_id' => $request->user()->id,
            'body'    => $request->input('body'),
        ]);

        $thread->increment('replies_count');
        $thread->update(['last_reply_at' => now()]);

        return redirect()->route('forum.show', $thread)
                         ->with('status', 'reply-added')
                         ->fragment('replies');
    }

    public function destroy(Request $request, ForumThread $thread): RedirectResponse
    {
        abort_if($request->user()->id !== $thread->user_id && !$request->user()->isAdmin(), 403);
        $thread->delete();
        return redirect()->route('forum.index')->with('status', 'thread-deleted');
    }

    public function destroyReply(Request $request, ForumReply $reply): RedirectResponse
    {
        abort_if($request->user()->id !== $reply->user_id && !$request->user()->isAdmin(), 403);
        $threadId = $reply->thread_id;
        $reply->thread->decrement('replies_count');
        $reply->delete();
        return redirect()->route('forum.show', $threadId)->with('status', 'reply-deleted');
    }
}
