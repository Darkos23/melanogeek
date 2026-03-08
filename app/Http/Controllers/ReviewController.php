<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order)
    {
        // Seul l'acheteur peut laisser un avis
        abort_if($order->buyer_id !== $request->user()->id, 403);
        // Uniquement sur une commande terminée
        abort_if($order->status !== 'completed', 422, 'La commande doit être terminée pour laisser un avis.');
        // Un seul avis par commande
        abort_if($order->review()->exists(), 422, 'Tu as déjà laissé un avis pour cette commande.');

        $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        Review::create([
            'order_id'    => $order->id,
            'reviewer_id' => $request->user()->id,
            'reviewed_id' => $order->seller_id,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
        ]);

        return back()->with('success', 'Merci pour ton avis !');
    }
}
