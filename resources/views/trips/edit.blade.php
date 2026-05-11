@extends('layouts.app')

@section('title', 'Edit Perjalanan')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">✏️ Edit Perjalanan</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('trips.update', $trip) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota Asal *</label>
                    <input type="text" name="origin_city" value="{{ old('origin_city', $trip->origin_city) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('origin_city')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota Tujuan *</label>
                    <input type="text" name="destination_city" value="{{ old('destination_city', $trip->destination_city) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('destination_city')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berangkat *</label>
                        <input type="date" name="departure_date" value="{{ old('departure_date', $trip->departure_date->format('Y-m-d')) }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Tiba</label>
                        <input type="date" name="arrival_date" value="{{ old('arrival_date', $trip->arrival_date?->format('Y-m-d')) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maksimal Titipan *</label>
                        <input type="number" name="max_requests" value="{{ old('max_requests', $trip->max_requests) }}" required min="1" max="10"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Transportasi</label>
                        <select name="transport_mode" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih...</option>
                            <option value="pesawat" {{ $trip->transport_mode === 'pesawat' ? 'selected' : '' }}>✈️ Pesawat</option>
                            <option value="kereta" {{ $trip->transport_mode === 'kereta' ? 'selected' : '' }}>🚂 Kereta</option>
                            <option value="mobil" {{ $trip->transport_mode === 'mobil' ? 'selected' : '' }}>🚗 Mobil</option>
                            <option value="bus" {{ $trip->transport_mode === 'bus' ? 'selected' : '' }}>🚌 Bus</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Bagasi</label>
                    <select name="baggage_capacity" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih...</option>
                        <option value="kecil" {{ $trip->baggage_capacity === 'kecil' ? 'selected' : '' }}>🎒 Kecil</option>
                        <option value="sedang" {{ $trip->baggage_capacity === 'sedang' ? 'selected' : '' }}>🧳 Sedang</option>
                        <option value="besar" {{ $trip->baggage_capacity === 'besar' ? 'selected' : '' }}>📦 Besar</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                    <textarea name="notes" rows="3" maxlength="500"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $trip->notes) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="open" {{ $trip->status === 'open' ? 'selected' : '' }}>🔓 Open</option>
                        <option value="full" {{ $trip->status === 'full' ? 'selected' : '' }}>🈵 Full</option>
                        <option value="completed" {{ $trip->status === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                        <option value="cancelled" {{ $trip->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('trips.show', $trip) }}" class="flex-1 text-center border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700">
                        💾 Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection