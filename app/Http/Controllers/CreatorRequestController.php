<?php

namespace App\Http\Controllers;

use App\Models\CreatorRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CreatorRequestController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Déjà creator ou staff
        if ($user->isCreator() || $user->isStaff()) {
            return back()->with('error', 'Vous êtes déjà créateur ou membre du staff.');
        }

        // Déjà une demande en cours
        if ($user->creatorRequest?->isPending()) {
            return back()->with('error', 'Vous avez déjà une demande en cours d\'examen.');
        }

        $request->validate([
            'motivation' => ['required', 'string', 'min:50', 'max:1000'],
        ], [
            'motivation.required' => 'Veuillez expliquer votre motivation.',
            'motivation.min'      => 'Votre message doit faire au moins 50 caractères.',
            'motivation.max'      => 'Votre message ne peut pas dépasser 1000 caractères.',
        ]);

        CreatorRequest::updateOrCreate(
            ['user_id' => $user->id],
            [
                'motivation'  => $request->motivation,
                'status'      => 'pending',
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]
        );

        return back()->with('success', 'Votre demande a bien été envoyée. Nous vous répondrons sous 48h.');
    }
}
