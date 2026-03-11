<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CreatorRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $isCreator = $request->account_type === 'creator';

        // Normaliser l'email en minuscules avant validation
        $request->merge(['email' => strtolower(trim($request->email))]);

        $request->validate([
            'beta_code'        => ['required', 'in:' . env('BETA_CODE', 'MELANO2026')],
            'account_type'     => ['required', 'in:fan,creator'],
            'name'             => ['required', 'string', 'max:255'],
            'username'         => ['required', 'string', 'min:3', 'max:30', 'unique:users,username', 'regex:/^[a-zA-Z0-9_.\\-]+$/'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
            'country_type'     => ['required', 'in:senegal,africa,diaspora'],
            'creator_category' => $isCreator ? ['required', 'string', 'max:100'] : ['nullable'],
            'creator_bio'      => $isCreator ? ['required', 'string', 'min:30', 'max:500'] : ['nullable'],
            'creator_social'   => $isCreator ? ['required', 'url', 'max:255'] : ['nullable', 'url', 'max:255'],
        ], [
            'beta_code.required'        => 'Le code d\'accès beta est requis.',
            'beta_code.in'              => 'Code d\'accès invalide. Contacte-nous pour obtenir un code.',
            'username.unique'           => 'Ce pseudo est déjà pris.',
            'username.regex'            => 'Le pseudo ne peut contenir que des lettres, chiffres, points, tirets et underscores.',
            'username.min'              => 'Le pseudo doit faire au moins 3 caractères.',
            'email.unique'              => 'Cette adresse email est déjà utilisée.',
            'password.confirmed'        => 'Les mots de passe ne correspondent pas.',
            'creator_category.required' => 'Choisis ta catégorie créateur.',
            'creator_bio.required'      => 'Présente-toi en tant que créateur.',
            'creator_bio.min'           => 'Ta présentation doit faire au moins 30 caractères.',
            'creator_social.required'   => 'Un lien vers ton contenu est obligatoire pour valider ta candidature.',
            'creator_social.url'        => 'Le lien doit être une URL valide (ex: https://instagram.com/…).',
        ]);

        $user = User::create([
            'name'             => $request->name,
            'username'         => strtolower($request->username),
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'country_type'     => $request->country_type,
            'plan'             => 'free',
            'role'             => $isCreator ? null : 'user',
            'status'           => $isCreator ? 'pending' : 'approved',
            'creator_category' => $isCreator ? $request->creator_category : null,
            'creator_bio'      => $isCreator ? $request->creator_bio : null,
            'creator_socials'  => ($isCreator && $request->creator_social) ? [$request->creator_social] : null,
        ]);

        // Créer la demande creator pour que l'admin puisse l'approuver
        if ($isCreator) {
            CreatorRequest::create([
                'user_id'    => $user->id,
                'motivation' => $user->creator_bio ?? 'Inscription via le formulaire créateur.',
                'status'     => 'pending',
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        if ($isCreator) {
            return redirect()->route('application.pending');
        }

        return redirect()->route('feed')->with('success', 'Bienvenue sur MelanoGeek, ' . $user->name . ' ! 🎉');
    }
}