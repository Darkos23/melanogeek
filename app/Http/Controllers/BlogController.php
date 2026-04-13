<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $category = request('category');

        $posts = Post::with('user')
            ->published()
            ->whereNotNull('title')
            ->where('title', '!=', '')
            ->when($category, fn($q) => $q->where('category', $category))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('blog.index', compact('posts', 'category'));
    }
}
