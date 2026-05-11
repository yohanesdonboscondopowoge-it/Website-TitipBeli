<?php
// app/Http/Controllers/TripController.php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function index(Request $request)
    {
        $query = Trip::with('user')->where('is_active', true);

        // Filter
        if ($request->filled('origin')) {
            $query->where('origin_city', 'like', '%' . $request->origin . '%');
        }
        if ($request->filled('destination')) {
            $query->where('destination_city', 'like', '%' . $request->destination . '%');
        }
        if ($request->filled('date')) {
            $query->whereDate('departure_date', $request->date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trips = $query->orderBy('departure_date', 'asc')->paginate(12);

        return view('trips.index', compact('trips'));
    }

    public function create()
    {
        return view('trips.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin_city' => 'required|string|max:100',
            'destination_city' => 'required|string|max:100',
            'departure_date' => 'required|date|after:today',
            'arrival_date' => 'nullable|date|after_or_equal:departure_date',
            'max_requests' => 'required|integer|min:1|max:10',
            'transport_mode' => 'nullable|string|max:50',
            'baggage_capacity' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['remaining_slots'] = $validated['max_requests'];
        $validated['status'] = 'open';

        Trip::create($validated);

        return redirect()->route('trips.index')->with('success', 'Perjalanan berhasil diposting! 🎉');
    }

    public function show(Trip $trip)
    {
        $trip->load('user', 'orders.requester');
        return view('trips.show', compact('trip'));
    }

    public function edit(Trip $trip)
    {
        $this->authorize('update', $trip);
        return view('trips.edit', compact('trip'));
    }

    public function update(Request $request, Trip $trip)
    {
        $this->authorize('update', $trip);

        $validated = $request->validate([
            'origin_city' => 'required|string|max:100',
            'destination_city' => 'required|string|max:100',
            'departure_date' => 'required|date',
            'arrival_date' => 'nullable|date',
            'max_requests' => 'required|integer|min:1',
            'transport_mode' => 'nullable|string|max:50',
            'baggage_capacity' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
            'status' => 'nullable|in:open,full,completed,cancelled',
        ]);

        $trip->update($validated);

        return redirect()->route('trips.show', $trip)->with('success', 'Perjalanan berhasil diperbarui! ✏️');
    }

    public function destroy(Trip $trip)
    {
        $this->authorize('delete', $trip);
        $trip->update(['is_active' => false]);
        return redirect()->route('trips.index')->with('success', 'Perjalanan dihapus! 🗑️');
    }

    // My Trips (Dashboard User)
    public function myTrips()
    {
        $trips = Trip::where('user_id', Auth::id())
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('trips.my', compact('trips'));
    }
}