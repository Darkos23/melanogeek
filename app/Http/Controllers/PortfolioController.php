<?php

namespace App\Http\Controllers;

use App\Models\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function create()
    {
        abort_unless(auth()->user()->isCreator(), 403);
        return view('portfolio.create', ['categories' => PortfolioItem::categories()]);
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->isCreator(), 403);

        $data = $request->validate([
            'title'        => ['required', 'string', 'max:120'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'category'     => ['required', 'string', 'in:' . implode(',', array_keys(PortfolioItem::categories()))],
            'external_url' => ['nullable', 'url', 'max:255'],
            'tags'         => ['nullable', 'string', 'max:200'],
            'cover_image'  => ['nullable', 'image', 'max:4096'],
            'images.*'     => ['nullable', 'image', 'max:4096'],
        ]);

        $data['user_id'] = auth()->id();

        // Cover image
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('portfolio', 'public');
        }

        // Additional images (max 6)
        $extras = [];
        if ($request->hasFile('images')) {
            foreach (array_slice($request->file('images'), 0, 6) as $file) {
                $extras[] = $file->store('portfolio', 'public');
            }
        }
        $data['images'] = $extras ?: null;

        // Tags → array
        $data['tags'] = $data['tags']
            ? array_values(array_filter(array_map('trim', explode(',', $data['tags']))))
            : null;

        PortfolioItem::create($data);

        return redirect()->route('profile.show', auth()->user()->username)
            ->with('success', 'Projet ajouté à ton portfolio !');
    }

    public function edit(PortfolioItem $portfolio)
    {
        abort_unless(auth()->id() === $portfolio->user_id, 403);
        return view('portfolio.edit', [
            'item'       => $portfolio,
            'categories' => PortfolioItem::categories(),
        ]);
    }

    public function update(Request $request, PortfolioItem $portfolio)
    {
        abort_unless(auth()->id() === $portfolio->user_id, 403);

        $data = $request->validate([
            'title'        => ['required', 'string', 'max:120'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'category'     => ['required', 'string', 'in:' . implode(',', array_keys(PortfolioItem::categories()))],
            'external_url' => ['nullable', 'url', 'max:255'],
            'tags'         => ['nullable', 'string', 'max:200'],
            'cover_image'  => ['nullable', 'image', 'max:4096'],
            'images.*'     => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($portfolio->cover_image) Storage::disk('public')->delete($portfolio->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('portfolio', 'public');
        }

        if ($request->hasFile('images')) {
            // Delete old extras
            foreach ($portfolio->images ?? [] as $old) {
                Storage::disk('public')->delete($old);
            }
            $extras = [];
            foreach (array_slice($request->file('images'), 0, 6) as $file) {
                $extras[] = $file->store('portfolio', 'public');
            }
            $data['images'] = $extras;
        }

        $data['tags'] = $data['tags']
            ? array_values(array_filter(array_map('trim', explode(',', $data['tags']))))
            : null;

        $portfolio->update($data);

        return redirect()->route('profile.show', auth()->user()->username)
            ->with('success', 'Projet mis à jour.');
    }

    public function destroy(PortfolioItem $portfolio)
    {
        abort_unless(auth()->id() === $portfolio->user_id, 403);

        if ($portfolio->cover_image) Storage::disk('public')->delete($portfolio->cover_image);
        foreach ($portfolio->images ?? [] as $img) {
            Storage::disk('public')->delete($img);
        }
        $portfolio->delete();

        return redirect()->route('profile.show', auth()->user()->username)
            ->with('success', 'Projet supprimé.');
    }
}
