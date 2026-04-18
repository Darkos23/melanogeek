<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;

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
            ->when($query, function ($q) use ($query) {
                $safe = '%'.Str::escapeLike($query).'%';
                $q->where(fn ($sub) =>
                    $sub->where('title', 'like', $safe)
                        ->orWhere('body', 'like', $safe)
                );
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('blog.index', compact('posts', 'category', 'query'));
    }
}
