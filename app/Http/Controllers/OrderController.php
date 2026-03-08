<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // ── Liste des commandes de l'utilisateur ──────────────────────
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'purchases'); // purchases | sales

        if ($tab === 'sales') {
            // Mes ventes (je suis le vendeur)
            $orders = Order::with(['service', 'buyer'])
                ->where('seller_id', auth()->id())
                ->latest()
                ->paginate(15);
        } else {
            // Mes achats (je suis l'acheteur)
            $orders = Order::with(['service', 'seller'])
                ->where('buyer_id', auth()->id())
                ->latest()
                ->paginate(15);
        }

        return view('orders.index', compact('orders', 'tab'));
    }

    // ── Créer une commande ────────────────────────────────────────
    public function store(Request $request)
    {
        $service = Service::findOrFail($request->service_id);
        abort_if(! $service->is_active, 404);
        abort_if($service->user_id === auth()->id(), 422, 'Tu ne peux pas commander ton propre service.');
        abort_if(! $service->user->is_available, 422, 'Ce créateur n\'accepte pas de nouvelles commandes pour l\'instant.');

        $request->validate([
            'service_id'     => ['required', 'exists:services,id'],
            'requirements'   => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['required', 'in:wave,orange_money,stripe'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
        ]);

        $order = Order::create([
            'service_id'     => $service->id,
            'buyer_id'       => auth()->id(),
            'seller_id'      => $service->user_id,
            'price'          => $service->price,
            'currency'       => $service->currency,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'requirements'   => $request->requirements,
        ]);

        // Incrémenter le compteur de commandes du service
        $service->increment('orders_count');

        return redirect()->route('orders.show', $order)
            ->with('success', 'Commande envoyée ! Le créateur va la traiter sous peu.');
    }

    // ── Détail d'une commande ─────────────────────────────────────
    public function show(Order $order)
    {
        abort_if(
            $order->buyer_id !== auth()->id() && $order->seller_id !== auth()->id(),
            403
        );
        $order->load(['service', 'buyer', 'seller']);
        return view('orders.show', compact('order'));
    }

    // ── Créateur : accepter ───────────────────────────────────────
    public function accept(Order $order)
    {
        abort_if($order->seller_id !== auth()->id(), 403);
        abort_if($order->status !== 'pending', 422);

        $order->update(['status' => 'accepted']);
        return back()->with('success', 'Commande acceptée ! Tu peux maintenant commencer la réalisation.');
    }

    // ── Créateur : démarrer la réalisation ───────────────────────
    public function startWork(Order $order)
    {
        abort_if($order->seller_id !== auth()->id(), 403);
        abort_if($order->status !== 'accepted', 422);

        $order->update(['status' => 'in_progress']);
        return back()->with('success', 'C\'est parti ! La commande est maintenant en cours de réalisation.');
    }

    // ── Créateur : marquer livré ──────────────────────────────────
    public function deliver(Request $request, Order $order)
    {
        abort_if($order->seller_id !== auth()->id(), 403);
        abort_if(! in_array($order->status, ['accepted', 'in_progress']), 422);

        $request->validate([
            'seller_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->update([
            'status'      => 'delivered',
            'seller_note' => $request->seller_note,
        ]);

        return back()->with('success', 'Livraison marquée ! L\'acheteur va confirmer la réception.');
    }

    // ── Acheteur : confirmer réception ────────────────────────────
    public function complete(Order $order)
    {
        abort_if($order->buyer_id !== auth()->id(), 403);
        abort_if($order->status !== 'delivered', 422);

        $order->update([
            'status'         => 'completed',
            'payment_status' => 'paid',
        ]);

        return back()->with('success', 'Commande terminée ! Merci pour ta confiance.');
    }

    // ── Annuler ───────────────────────────────────────────────────
    public function cancel(Order $order)
    {
        abort_if(! $order->canBeCancelledBy(auth()->user()), 403);
        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Commande annulée.');
    }
}
