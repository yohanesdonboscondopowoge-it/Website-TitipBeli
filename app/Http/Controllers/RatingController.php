<?php
// app/Http/Controllers/RatingController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Order $order)
    {
        // Cek apakah user participant dan order completed
        if (!$order->isParticipant(Auth::user()) || $order->status !== 'completed') {
            abort(403, 'Tidak bisa memberi rating pada order ini.');
        }

        // Cek apakah sudah pernah rating
        $existingRating = Rating::where('order_id', $order->id)
            ->where('rater_id', Auth::id())
            ->first();

        if ($existingRating) {
            return back()->with('error', 'Kamu sudah memberi rating pada transaksi ini.');
        }

        $validated = $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Tentukan siapa yang di-rate
        $ratedUserId = $order->traveller_id === Auth::id()
            ? $order->requester_id
            : $order->traveller_id;

        Rating::create([
            'order_id' => $order->id,
            'rater_id' => Auth::id(),
            'rated_user_id' => $ratedUserId,
            'score' => $validated['score'],
            'comment' => $validated['comment'],
        ]);

        // Update rating avg untuk user yang di-rate
        $ratedUser = \App\Models\User::find($ratedUserId);
        $ratedUser->rating_avg = Rating::where('rated_user_id', $ratedUserId)->avg('score') ?? 0;
        $ratedUser->total_ratings = Rating::where('rated_user_id', $ratedUserId)->count();
        $ratedUser->recalculateTrustScore();

        return back()->with('success', 'Terima kasih sudah memberi rating! ⭐');
    }
}