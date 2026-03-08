<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // Plans disponibles avec leur config
    const PLANS = [
        'african' => [
            'name'        => 'Plan Afrique',
            'tagline'     => 'Pour la communauté africaine',
            'amount'      => 2500,
            'currency'    => 'XOF',
            'amount_str'  => '2 500 FCFA',
            'period'      => '/mois',
            'methods'     => ['wave', 'orange_money'],
            'features'    => [
                'Tout du plan Sénégal',
                'Badge 🌍 Afrique sur ton profil',
                'Paiement Wave & Orange Money',
                'Réponse support sous 48h',
            ],
            'color'       => '#D4A843',
            'icon'        => '🌍',
        ],
        'global' => [
            'name'        => 'Plan Diaspora',
            'tagline'     => 'Pour la diaspora sénégalaise',
            'amount'      => 9.99,
            'currency'    => 'EUR',
            'amount_str'  => '9,99 €',
            'period'      => '/mois',
            'methods'     => ['stripe'],
            'features'    => [
                'Tout du plan Afrique',
                'Badge ✈️ Diaspora sur ton profil',
                'Paiement par carte bancaire',
                'Réponse support sous 24h',
            ],
            'color'       => '#C8522A',
            'icon'        => '✈️',
        ],
    ];

    // Numéros marchands (à configurer)
    const MERCHANT = [
        'wave'         => '77 000 00 00',
        'orange_money' => '77 000 00 00',
    ];

    public function pricing()
    {
        $activeSub = auth()->check()
            ? Subscription::where('user_id', auth()->id())
                ->whereIn('status', ['active', 'pending'])
                ->latest()
                ->first()
            : null;

        return view('subscription.pricing', [
            'plans'     => self::PLANS,
            'activeSub' => $activeSub,
        ]);
    }

    public function checkout(string $plan)
    {
        abort_if(! array_key_exists($plan, self::PLANS), 404);

        $activeSub = Subscription::where('user_id', auth()->id())
            ->whereIn('status', ['active', 'pending'])
            ->latest()
            ->first();

        return view('subscription.checkout', [
            'plan'      => $plan,
            'details'   => self::PLANS[$plan],
            'merchant'  => self::MERCHANT,
            'activeSub' => $activeSub,
        ]);
    }

    public function store(Request $request, string $plan)
    {
        abort_if(! array_key_exists($plan, self::PLANS), 404);

        $details = self::PLANS[$plan];

        $request->validate([
            'payment_method' => ['required', 'in:' . implode(',', $details['methods'])],
            'transaction_id' => ['nullable', 'string', 'max:100'],
        ]);

        // Pas de doublon pending
        $hasPending = Subscription::where('user_id', auth()->id())
            ->where('plan', $plan)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->with('info', 'Tu as déjà un abonnement en attente de validation pour ce plan.');
        }

        Subscription::create([
            'user_id'        => auth()->id(),
            'plan'           => $plan,
            'amount'         => $details['amount'],
            'currency'       => $details['currency'],
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'status'         => 'pending',
        ]);

        return redirect()->route('subscription.pricing')
            ->with('success', 'Demande envoyée ! Ton abonnement sera activé après vérification (sous 24h).');
    }

    public function cancel(Request $request)
    {
        $sub = Subscription::where('user_id', $request->user()->id)
            ->whereIn('status', ['active', 'pending'])
            ->latest()
            ->firstOrFail();

        $sub->update(['status' => 'cancelled']);

        return redirect()->route('subscription.pricing')
            ->with('success', 'Ton abonnement a été annulé. Il restera actif jusqu\'à sa date d\'expiration.');
    }
}
