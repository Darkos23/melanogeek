<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AboutController extends Controller
{
    public function index(): JsonResponse
    {
        $fields = ['about_title', 'about_tagline', 'about_mission', 'about_story', 'about_values'];
        $content = [];
        foreach ($fields as $key) {
            $content[$key] = Setting::get($key, '');
        }

        $stats = [
            'users_count'    => User::where('is_active', true)->where('role', '!=', 'owner')->count(),
            'creators_count' => User::where('role', 'creator')->count(),
            'posts_count'    => Post::where('is_published', true)->count(),
        ];

        return response()->json([
            'content' => $content,
            'stats'   => $stats,
        ]);
    }
}
