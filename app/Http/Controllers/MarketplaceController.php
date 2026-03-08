<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('user')->active();

        if ($cat = $request->category) {
            $query->where('category', $cat);
        }

        if ($q = $request->search) {
            $query->where(function ($sq) use ($q) {
                $sq->where('title', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($sort = $request->sort) {
            match ($sort) {
                'price_asc'  => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'popular'    => $query->orderBy('orders_count', 'desc'),
                default      => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $services   = $query->paginate(12)->withQueryString();
        $categories = Service::CATEGORIES;

        return view('marketplace.index', compact('services', 'categories'));
    }

    public function show(Service $service)
    {
        abort_if(! $service->is_active, 404);
        $service->load('user');

        // Autres services du même créateur
        $moreServices = Service::where('user_id', $service->user_id)
            ->where('id', '!=', $service->id)
            ->active()
            ->limit(3)
            ->get();

        // Avis sur les services de ce créateur
        $reviews = Review::with('reviewer:id,name,username,avatar')
            ->where('reviewed_id', $service->user_id)
            ->latest()
            ->limit(10)
            ->get();

        $avgRating   = Review::avgFor($service->user_id);
        $totalReviews = Review::countFor($service->user_id);

        return view('marketplace.show', compact('service', 'moreServices', 'reviews', 'avgRating', 'totalReviews'));
    }
}
