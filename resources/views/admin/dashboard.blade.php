@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h1 class="text-3xl font-extrabold text-gray-900 mb-8">📊 Dashboard Admin</h1>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm text-gray-500 font-medium">Total Users</p>
        <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm text-gray-500 font-medium">Total Orders</p>
        <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ $stats['total_orders'] }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm text-gray-500 font-medium">Selesai</p>
        <p class="text-4xl font-extrabold text-green-600 mt-2">{{ $stats['completed_orders'] }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm text-gray-500 font-medium">Dispute</p>
        <p class="text-4xl font-extrabold text-red-600 mt-2">{{ $stats['disputed_orders'] }}</p>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">📦 Order Terbaru</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Traveller</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requester</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-mono">#{{ substr($order->id, 0, 8) }}</td>
                    <td class="px-4 py-3 text-sm">{{ $order->traveller->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">{{ $order->requester->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm font-medium">
                        Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        Belum ada order
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection