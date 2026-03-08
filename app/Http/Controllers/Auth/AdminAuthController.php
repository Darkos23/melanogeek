<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function create(): View
    {
        return view('auth.admin-login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Vérifier que l'utilisateur est bien staff (admin ou owner)
        if (! $request->user()->isStaff()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Accès refusé. Ce portail est réservé au staff MelanoGeek.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Owner → son dashboard dédié
        if ($request->user()->isOwner()) {
            return redirect()->route('owner.dashboard');
        }

        return redirect()->route('admin.dashboard');
    }
}
