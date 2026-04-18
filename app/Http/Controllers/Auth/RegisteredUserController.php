<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Post;
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
        return view('auth.register', [
            'membersCount' => User::count(),
            'postsCount'   => Post::published()->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Normaliser l'email en minuscules avant validation
        $request->merge(['email' => strtolower(trim($request->email))]);

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'unique:users,username', 'regex:/^[a-zA-Z0-9_.\\-]+$/'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'username.unique'    => 'Ce pseudo est déjà pris.',
            'username.regex'     => 'Le pseudo ne peut contenir que des lettres, chiffres, points, tirets et underscores.',
            'username.min'       => 'Le pseudo doit faire au moins 3 caractères.',
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => strtolower($request->username),
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
            'status'   => 'approved',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Bienvenue sur MelanoGeek, ' . $user->name . ' ! 🎉');
    }
}
