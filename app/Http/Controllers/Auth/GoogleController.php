<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirige vers la page d'authentification Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Traite le retour de Google après authentification.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Connexion Google échouée. Réessaie ou utilise ton email.');
        }

        // Chercher l'utilisateur par google_id ou par email
        $user = User::where('google_id', $googleUser->getId())->first()
              ?? User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Lier le google_id si l'utilisateur existait déjà par email
            if (! $user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            // Vérifier que le compte est actif
            if (! $user->is_active) {
                return redirect()->route('login')
                    ->with('error', 'Ton compte a été désactivé. Contacte le support.');
            }
        } else {
            // Créer un nouveau compte automatiquement
            $user = User::create([
                'name'         => $googleUser->getName() ?? 'Utilisateur',
                'username'     => $this->generateUsername($googleUser->getName() ?? 'user'),
                'email'        => $googleUser->getEmail(),
                'google_id'    => $googleUser->getId(),
                'password'     => bcrypt(Str::random(32)),
                'country_type' => 'senegal',  // par défaut
                'plan'         => 'free',
                'is_active'    => true,
                'is_verified'  => true,       // Google garantit l'email vérifié
            ]);
        }

        Auth::login($user, remember: true);

        return redirect()->route('home');
    }

    /**
     * Génère un username unique à partir du nom Google.
     */
    private function generateUsername(string $name): string
    {
        // Nettoyer : garder seulement les lettres et chiffres, max 20 chars
        $base = substr(preg_replace('/[^a-z0-9]/', '', Str::lower(Str::ascii($name))), 0, 20);
        $base = $base ?: 'user';

        $username = $base;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $i;
            $i++;
        }

        return $username;
    }
}
