<?php
// app/Http/Controllers/RequestController.php

namespace App\Http\Controllers;

use App\Models\TitipRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $query = TitipRequest::with('user')->where('is_active', true);

        if ($request->filled('origin')) {
            $query->where('origin_city', 'like', '%' . $request->origin . '%');
        }
        if ($request->filled('destination')) {
            $query->where('destination_city', 'like', '%' . $request->destination . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('requests.index', compact('requests'));
    }

    public function create()
    {
        return view('requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:100',
            'origin_city' => 'required|string|max:100',
            'destination_city' => 'required|string|max:100',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date|after:today',
            'weight_estimate' => 'nullable|string|max:50',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('requests', 'public');
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'open';

        TitipRequest::create($validated);

        return redirect()->route('requests.index')->with('success', 'Permintaan titip berhasil dibuat! 📝');
    }

    public function show(TitipRequest $titipRequest)
    {
        $titipRequest->load('user');
        return view('requests.show', compact('titipRequest'));
    }

    public function edit(TitipRequest $titipRequest)
    {
        $this->authorize('update', $titipRequest);
        return view('requests.edit', compact('titipRequest'));
    }

    public function update(Request $request, TitipRequest $titipRequest)
    {
        $this->authorize('update', $titipRequest);

        $validated = $request->validate([
            'item_name' => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:100',
            'origin_city' => 'required|string|max:100',
            'destination_city' => 'required|string|max:100',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date',
            'weight_estimate' => 'nullable|string|max:50',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($titipRequest->image) {
                Storage::disk('public')->delete($titipRequest->image);
            }
            $validated['image'] = $request->file('image')->store('requests', 'public');
        }

        $titipRequest->update($validated);

        return redirect()->route('requests.show', $titipRequest)->with('success', 'Permintaan berhasil diperbarui! ✏️');
    }

    public function destroy(TitipRequest $titipRequest)
    {
        $this->authorize('delete', $titipRequest);
        $titipRequest->update(['is_active' => false]);
        return redirect()->route('requests.index')->with('success', 'Permintaan dihapus! 🗑️');
    }

    public function myRequests()
    {
        $requests = TitipRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('requests.my', compact('requests'));
    }
}