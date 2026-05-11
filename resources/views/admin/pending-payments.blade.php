@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<h1 class="text-3xl font-extrabold text-gray-900 mb-8">💰 Verifikasi Pembayaran</h1>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Traveller</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requester</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bukti</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-mono">#{{ substr($order->id, 0, 8) }}</td>
                    <td class="px-4 py-3 text-sm">{{ $order->traveller->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">{{ $order->requester->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-medium">
                        Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3">
                        @if($order->payment_proof)
                            <a href="{{ Storage::url($order->payment_proof) }}" target="_blank" 
                               class="text-blue-600 hover:underline text-sm">
                                📄 Lihat Bukti
                            </a>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <form action="{{ route('admin.verify-payment', $order) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-green-700 transition-all"
                                        onclick="return confirm('Verifikasi pembayaran ini?')">
                                    ✅ Verifikasi
                                </button>
                            </form>
                            <form action="{{ route('admin.reject-payment', $order) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-700 transition-all"
                                        onclick="return confirm('Tolak pembayaran ini?')">
                                    ❌ Tolak
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                        🎉 Tidak ada pembayaran yang perlu diverifikasi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection