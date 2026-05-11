@extends('layouts.app')

@section('title', 'Perjalanan Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">✈️ Perjalanan Saya</h1>
        <a href="{{ route('trips.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Posting Baru
        </a>
    </div>

    @if($trips->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rute</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slot</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($trips as $trip)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $trip->origin_city }} → {{ $trip->destination_city }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $trip->departure_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $trip->remaining_slots }}/{{ $trip->max_requests }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $trip->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $trip->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('trips.show', $trip) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $trips->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
            <div class="text-6xl mb-4">✈️</div>
            <p class="text-gray-600 mb-4">Kamu belum punya perjalanan</p>            <p class="text-gray-600 mb-4">Kamu belum punya perjalanan</p>
            <p class="text-gray-500 mb-6">Posting perjalananmu sekarang dan mulai dapat titipan!</p>
            <a href="{{ route('trips.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium">
                ✈️ Posting Perjalanan Pertama
            </a>
        </div>
    @endif
</div>
@endsection