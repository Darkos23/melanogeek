<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostAdminController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with('user')
            ->when($request->search, fn($q) => $q->where('title', 'like', '%'.$request->search.'%'))
            ->when($request->has('published') && $request->published !== '', fn($q) => $q->where('is_published', $request->published))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function toggle(Post $post)
    {
        $post->update(['is_published' => !$post->is_published]);
        return back()->with('success', 'Publication mise à jour.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Publication supprimée.');
    }
}
