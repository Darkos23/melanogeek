<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $category = request('category');
        $query    = request('q');

        $posts = Post::with('user')
            ->published()
            ->whereNotNull('title')
            ->where('title', '!=', '')
            ->when($category, fn($q) => $q->where('category', $category))
            ->when($query, fn($q) =>
                $q->where(fn($sub) =>
                    $sub->where('title', 'like', "%{$query}%")
                        ->orWhere('body', 'like', "%{$query}%")
                )
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('blog.index', compact('posts', 'category', 'query'));
    }
}
