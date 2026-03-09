<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                abort_if(! auth()->user()->isCreator(), 403, 'Seuls les créateurs peuvent proposer des services.');
                return $next($request);
            }, except: ['index']),
        ];
    }

    public function manage()
    {
        $services = Service::where('user_id', auth()->id())
            ->withCount('orders')
            ->latest()
            ->get();

        return view('services.manage', compact('services'));
    }

    public function create()
    {
        $categories = Service::CATEGORIES;
        return view('services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $isQuote = $request->input('price_type') === 'quote';

        $data = $request->validate([
            'title'         => ['required', 'string', 'max:120'],
            'description'   => ['required', 'string', 'max:2000'],
            'category'      => ['required', 'in:' . implode(',', array_keys(Service::CATEGORIES))],
            'price_type'    => ['required', 'in:fixed,quote'],
            'price'         => [$isQuote ? 'nullable' : 'required', 'numeric', 'min:100'],
            'currency'      => ['required', 'in:XOF,EUR'],
            'delivery_days' => ['required', 'integer', 'min:1', 'max:90'],
            'cover_image'   => ['nullable', 'image', 'max:4096'],
        ]);

        if ($isQuote) {
            $data['price'] = null;
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('services', 'public');
        }

        $data['user_id'] = auth()->id();

        Service::create($data);

        return redirect()->route('services.manage')
            ->with('success', 'Service créé ! Il est maintenant visible sur le marketplace.');
    }

    public function edit(Service $service)
    {
        abort_if($service->user_id !== auth()->id(), 403);
        $categories = Service::CATEGORIES;
        return view('services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        abort_if($service->user_id !== auth()->id(), 403);

        $isQuote = $request->input('price_type') === 'quote';

        $data = $request->validate([
            'title'         => ['required', 'string', 'max:120'],
            'description'   => ['required', 'string', 'max:2000'],
            'category'      => ['required', 'in:' . implode(',', array_keys(Service::CATEGORIES))],
            'price_type'    => ['required', 'in:fixed,quote'],
            'price'         => [$isQuote ? 'nullable' : 'required', 'numeric', 'min:100'],
            'currency'      => ['required', 'in:XOF,EUR'],
            'delivery_days' => ['required', 'integer', 'min:1', 'max:90'],
            'cover_image'   => ['nullable', 'image', 'max:4096'],
            'is_active'     => ['boolean'],
        ]);

        if ($isQuote) {
            $data['price'] = null;
        }

        if ($request->hasFile('cover_image')) {
            if ($service->cover_image) Storage::disk('public')->delete($service->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('services', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        $service->update($data);

        return redirect()->route('services.manage')
            ->with('success', 'Service mis à jour.');
    }

    public function destroy(Service $service)
    {
        abort_if($service->user_id !== auth()->id(), 403);
        if ($service->cover_image) Storage::disk('public')->delete($service->cover_image);
        $service->delete();
        return back()->with('success', 'Service supprimé.');
    }
}
