<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Trip;
use App\Models\TitipRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // User mengajukan titip ke trip
    public function createFromTrip(Request $request, Trip $trip)
    {
        // Pastikan trip masih open dan ada slot
        if ($trip->status !== 'open' || $trip->remaining_slots <= 0) {
            return back()->with('error', 'Maaf, perjalanan ini sudah penuh atau tidak tersedia.');
        }

        // Pastikan bukan traveller sendiri
        if ($trip->user_id === Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa titip ke perjalanan sendiri.');
        }

        $validated = $request->validate([
            'item_name' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'budget' => 'required|numeric|min:1000',
            'notes_from_requester' => 'nullable|string|max:500',
        ]);

        // Buat order
        $order = Order::create([
            'trip_id' => $trip->id,
            'traveller_id' => $trip->user_id,
            'requester_id' => Auth::id(),
            'status' => 'pending',
            'agreed_price' => $validated['budget'],
            'total_amount' => $validated['budget'],
            'notes_from_requester' => $validated['notes_from_requester'],
        ]);

        // Tambah log
        $order->addLog('pending', 'Permintaan titip dibuat', Auth::user());

        // Kurangi remaining slot
        $trip->decrement('remaining_slots');
        if ($trip->remaining_slots <= 0) {
            $trip->update(['status' => 'full']);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Permintaan titip berhasil dikirim! 🎉');
    }

    // Traveller accept/reject order
    // Traveller accept/reject order
public function updateStatus(Request $request, Order $order)
{
    $user = Auth::user();

    // Hanya traveller yang bisa accept/reject
    if ($order->traveller_id !== $user->id) {
        abort(403);
    }

    $validated = $request->validate([
        'status' => 'required|in:accepted,cancelled',
        'notes' => 'nullable|string|max:500',
    ]);

    $order->status = $validated['status'];
    $order->save();

    $order->addLog($validated['status'], $validated['notes'] ?? null, $user);

    $message = $validated['status'] === 'accepted'
        ? 'Permintaan titip diterima! Silakan tunggu info pembayaran. ✅'
        : 'Permintaan titip ditolak. ❌';

    return back()->with('success', $message);
}

    // Requester upload bukti pembayaran escrow
    public function uploadPayment(Request $request, Order $order)
    {
        if ($order->requester_id !== Auth::id() || $order->status !== 'accepted') {
            abort(403);
        }

        $validated = $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        $order->payment_proof = $request->file('payment_proof')->store('payments', 'public');
        $order->status = 'payment_uploaded';
        $order->paid_at = now();
        $order->save();

        $order->addLog('payment_uploaded', 'Bukti pembayaran diupload, menunggu verifikasi', Auth::user());

        return back()->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin. 📄');
    }

    // Traveller update status pengiriman
    public function updateDelivery(Request $request, Order $order)
{
    if ($order->traveller_id !== Auth::id()) {
        abort(403);
    }

    if (!in_array($order->status, ['payment_verified', 'purchased', 'in_transit'])) {
        abort(403, 'Status order tidak memungkinkan update delivery.');
    }

    $validated = $request->validate([
        'status' => 'required|in:purchased,in_transit,delivered',
        'item_photo' => 'nullable|image|max:2048',
        'notes' => 'nullable|string|max:500',
    ]);

    $order->status = $validated['status'];
    if ($request->hasFile('item_photo')) {
        $order->item_photo = $request->file('item_photo')->store('items', 'public');
    }
    if ($validated['status'] === 'delivered') {
        $order->delivered_at = now();
    }
    $order->save();

    $order->addLog($validated['status'], $validated['notes'] ?? null, Auth::user());

    $messages = [
        'purchased' => 'Status: Barang sudah dibeli! 🛍️',
        'in_transit' => 'Status: Barang dalam perjalanan! 🚀',
        'delivered' => 'Barang sudah sampai! Menunggu konfirmasi penerima. 📦',
    ];

    return back()->with('success', $messages[$validated['status']]);
}

    // Requester dispute / laporkan masalah
    public function dispute(Request $request, Order $order)
    {
        if (!$order->isParticipant(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $order->status = 'disputed';
        $order->save();

        $order->addLog('disputed', $validated['reason'], Auth::user());

        return back()->with('success', 'Laporan berhasil dikirim. Admin akan meninjau dalam 1x24 jam. 🚨');
    }

    // Admin verifikasi pembayaran (ESCROW)
    public function verifyPayment(Order $order)
    {
        // Hanya admin yang bisa
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        $order->status = 'payment_verified';
        $order->save();

        $order->addLog('payment_verified', 'Pembayaran diverifikasi oleh admin. Traveller bisa mulai membeli.', Auth::user());

        return back()->with('success', 'Pembayaran berhasil diverifikasi! ✅');
    }

    // Admin reject pembayaran
    public function rejectPayment(Request $request, Order $order)
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'note' => 'required|string|max:500',
        ]);

        $order->status = 'payment_uploaded'; // Kembalikan ke status sebelumnya
        $order->payment_proof = null;
        $order->paid_at = null;
        $order->save();

        $order->addLog('payment_rejected', $validated['note'], Auth::user());

        return back()->with('success', 'Pembayaran ditolak. Requester harus upload ulang.');
    }

    // Cancel order (oleh requester sebelum accepted)
    public function cancel(Order $order)
    {
        if ($order->requester_id !== Auth::id() || !in_array($order->status, ['pending', 'accepted', 'payment_uploaded'])) {
            abort(403);
        }

        $order->status = 'cancelled';
        $order->save();

        $order->addLog('cancelled', 'Dibatalkan oleh peminta', Auth::user());

        // Kembalikan slot trip
        $trip = $order->trip;
        if ($trip) {
            $trip->increment('remaining_slots');
            if ($trip->status === 'full') {
                $trip->update(['status' => 'open']);
            }
        }

        return back()->with('success', 'Order dibatalkan. ↩️');
    }

    // Tampilkan detail order
    public function show(Order $order)
    {
        // Cek apakah user adalah participant
        if (!$order->isParticipant(Auth::user())) {
            abort(403);
        }

        $order->load([
            'traveller',
            'requester',
            'trip',
            'titipRequest',
            'logs.user',
            'ratings',
        ]);

        return view('orders.show', compact('order'));
    }

    // List order untuk user yang login
    public function index(Request $request)
    {
        $user = Auth::user();

        $type = $request->get('type', 'all'); // all, as_traveller, as_requester

        $query = Order::with(['traveller', 'requester', 'trip'])
            ->where(function ($q) use ($user) {
                $q->where('traveller_id', $user->id)
                    ->orWhere('requester_id', $user->id);
            });

        if ($type === 'as_traveller') {
            $query->where('traveller_id', $user->id);
        } elseif ($type === 'as_requester') {
            $query->where('requester_id', $user->id);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('orders.index', compact('orders', 'type'));
    }

    // Requester konfirmasi terima
public function confirmReceived(Order $order)
{
    if ($order->requester_id !== Auth::id()) {
        abort(403);
    }

    if ($order->status !== 'delivered') {
        abort(403, 'Barang belum dikirim.');
    }

    $order->status = 'completed';
    $order->completed_at = now();
    $order->save();

    $order->addLog('completed', 'Barang diterima oleh peminta. Transaksi selesai! 🎉', Auth::user());

    // Update statistik traveller
    $traveller = $order->traveller;
    $traveller->increment('total_trips');
    $traveller->recalculateTrustScore();

    // Update statistik requester
    $requester = $order->requester;
    $requester->recalculateTrustScore();

    // Update trip kalau semua order selesai
    if ($order->trip) {
        $pendingOrders = $order->trip->orders()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        if ($pendingOrders === 0) {
            $order->trip->update(['status' => 'completed']);
        }
    }

    return redirect()->route('orders.show', $order)->with('success', 'Transaksi selesai! Jangan lupa beri rating ya! ⭐');
}
}