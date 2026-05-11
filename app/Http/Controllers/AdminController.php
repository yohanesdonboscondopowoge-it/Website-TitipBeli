<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'disputed_orders' => Order::where('status', 'disputed')->count(),
        ];

        $recentOrders = Order::with(['traveller', 'requester'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }

    // Show edit user form
public function editUser(User $user)
{
    return view('admin.edit-user', compact('user'));
}

// Update user
public function updateUser(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:50|unique:users,username,' . $user->id,
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'city' => 'nullable|string|max:100',
        'is_admin' => 'nullable|boolean',
        'is_ktp_verified' => 'nullable|boolean',
        'is_phone_verified' => 'nullable|boolean',
    ]);

    $user->update($validated);
    $user->recalculateTrustScore();

    return redirect()->route('admin.users')->with('success', 'User berhasil diupdate! ✅');
}

// Reset password user
public function resetPassword(Request $request, User $user)
{
    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user->password = Hash::make($request->password);
    $user->save();

    return back()->with('success', 'Password user berhasil direset! 🔒');
}

// Ban user (set is_active = false)
public function toggleBan(User $user)
{
    $user->is_active = !$user->is_active;
    $user->save();

    $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
    return back()->with('success', "User berhasil {$status}! ✅");
}

// Delete user
public function destroyUser(User $user)
{
    $user->delete();
    return redirect()->route('admin.users')->with('success', 'User berhasil dihapus! 🗑️');
}

    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function pendingPayments()
    {
        $orders = Order::with(['traveller', 'requester'])
            ->where('status', 'payment_uploaded')
            ->latest()
            ->paginate(20);

        return view('admin.pending-payments', compact('orders'));
    }

    public function disputes()
    {
        $orders = Order::with(['traveller', 'requester'])
            ->where('status', 'disputed')
            ->latest()
            ->paginate(20);

        return view('admin.disputes', compact('orders'));
    }

    public function verifyPayment(Order $order)
    {
        $order->status = 'payment_verified';
        $order->save();

        return back()->with('success', 'Pembayaran berhasil diverifikasi! ✅');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        $order->status = 'payment_rejected';
        $order->payment_proof = null;
        $order->save();

        return back()->with('success', 'Pembayaran ditolak.');
    }

    public function resolveDispute(Request $request, Order $order)
{
    $request->validate([
        'action' => 'required|in:complete,cancel,refund',
        'note' => 'nullable|string|max:500',
    ]);

    $user = auth()->user();

    switch ($request->action) {
        case 'complete':
            $order->status = 'completed';
            $order->completed_at = now();
            $order->addLog('completed', 'Dispute diselesaikan - Transaksi selesai. ' . $request->note, $user);
            
            // Update stats
            $order->traveller->increment('total_trips');
            $order->traveller->recalculateTrustScore();
            $order->requester->recalculateTrustScore();
            break;
            
        case 'cancel':
            $order->status = 'cancelled';
            $order->addLog('cancelled', 'Dispute diselesaikan - Transaksi dibatalkan. ' . $request->note, $user);
            
            // Kembalikan slot trip
            if ($order->trip) {
                $order->trip->increment('remaining_slots');
                if ($order->trip->status === 'full') {
                    $order->trip->update(['status' => 'open']);
                }
            }
            break;
            
        case 'refund':
            $order->status = 'cancelled';
            $order->addLog('cancelled', 'Dispute diselesaikan - Refund ke requester. ' . $request->note, $user);
            
            if ($order->trip) {
                $order->trip->increment('remaining_slots');
                if ($order->trip->status === 'full') {
                    $order->trip->update(['status' => 'open']);
                }
            }
            break;
    }

    $order->save();

    return back()->with('success', 'Dispute berhasil diselesaikan! ✅');
}
}