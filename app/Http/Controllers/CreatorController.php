<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->where('is_active', true)
            ->where('role', 'creator')
            ->withCount(['followers', 'posts']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        if ($niche = $request->get('niche')) {
            $query->where('niche', $niche);
        }

        $creators = $query->latest()->paginate(20)->withQueryString();

        $niches = User::where('is_active', true)
            ->where('role', 'creator')
            ->whereNotNull('niche')
            ->distinct()
            ->pluck('niche')
            ->sort()
            ->values();

        return view('creators.index', compact('creators', 'niches'));
    }
}
