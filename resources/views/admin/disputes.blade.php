@extends('layouts.admin')

@section('title', 'Dispute')

@section('content')
<h1 class="text-3xl font-extrabold text-gray-900 mb-8">🚨 Order Dispute</h1>

<div class="space-y-6">
    @forelse($orders as $order)
    <div class="bg-white rounded-2xl shadow-sm border border-red-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="font-mono text-sm font-bold text-gray-900">#{{ substr($order->id, 0, 8) }}</span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                        🚨 {{ $order->status }}
                    </span>
                </div>
                <p class="text-sm text-gray-500">
                    Dibuat: {{ $order->created_at->format('d M Y H:i') }}
                </p>
            </div>
            <div>
                <span class="text-lg font-bold text-red-600">
                    Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 bg-gray-50 rounded-xl">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">✈️ Traveller</p>
                <p class="font-semibold text-gray-900">{{ $order->traveller->name }}</p>
                <p class="text-sm text-gray-500">⭐ {{ number_format($order->traveller->rating_avg, 1) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">🛍️ Requester</p>
                <p class="font-semibold text-gray-900">{{ $order->requester->name }}</p>
                <p class="text-sm text-gray-500">⭐ {{ number_format($order->requester->rating_avg, 1) }}</p>
            </div>
        </div>

        <!-- Logs -->
        <div class="mb-4">
            <p class="text-sm font-semibold text-gray-700 mb-2">📋 Riwayat:</p>
            <div class="space-y-2 max-h-40 overflow-y-auto">
                @foreach($order->logs->sortByDesc('created_at') as $log)
                <div class="flex gap-2 text-sm">
                    <span class="text-gray-400">{{ $log->created_at->format('d/m H:i') }}</span>
                    <span class="font-medium">{{ $log->status }}</span>
                    @if($log->note)
                        <span class="text-gray-500">- {{ $log->note }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Payment Proof -->
        @if($order->payment_proof)
        <div class="mb-4">
            <a href="{{ Storage::url($order->payment_proof) }}" target="_blank" 
               class="text-blue-600 hover:underline text-sm">
                📄 Lihat Bukti Pembayaran
            </a>
        </div>
        @endif

        <!-- Action -->
        <div class="border-t border-gray-200 pt-4">
            <p class="text-sm font-semibold text-gray-700 mb-3">⚡ Resolusi:</p>
            
            <form action="{{ route('admin.resolve-dispute', $order) }}" method="POST">
                @csrf
                <input type="hidden" name="action" id="action-{{ $order->id }}" value="">
                
                <div class="flex flex-wrap gap-3 mb-3">
                    <button type="button" onclick="resolve('complete', '{{ $order->id }}')" 
                            class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-all">
                        ✅ Selesaikan (Complete)
                    </button>
                    <button type="button" onclick="resolve('cancel', '{{ $order->id }}')" 
                            class="bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-700 transition-all">
                        ❌ Batalkan (Cancel)
                    </button>
                    <button type="button" onclick="resolve('refund', '{{ $order->id }}')" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-all">
                        💰 Refund
                    </button>
                </div>
                
                <textarea name="note" rows="2" placeholder="Catatan resolusi..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3"></textarea>
                
                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium text-sm">
                    🚀 Eksekusi Resolusi
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="text-6xl mb-4">🎉</div>
        <p class="text-xl text-gray-600 font-medium">Tidak ada dispute!</p>
        <p class="text-gray-400">Semua transaksi berjalan lancar.</p>
    </div>
    @endforelse

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>

<script>
function resolve(action, orderId) {
    if (confirm('Yakin ingin menyelesaikan dispute ini dengan ' + action + '?')) {
        document.getElementById('action-' + orderId).value = action;
        event.target.closest('form').submit();
    }
}
</script>
@endsection