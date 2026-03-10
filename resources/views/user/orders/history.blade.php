@extends('layouts.user')
@section('title', 'Riwayat Pesanan')

@section('content')
<!-- Ambient Effects layer -->
<div class="fixed inset-0 pointer-events-none z-[-1] overflow-hidden bg-zinc-50">
    <div class="absolute -top-1/4 -right-1/4 w-3/4 h-3/4 bg-blue-300/30 rounded-full blur-[120px]"></div>
    <div class="absolute top-1/2 -left-1/4 w-3/4 h-3/4 bg-purple-300/30 rounded-full blur-[100px]"></div>
    <div class="absolute -bottom-1/4 left-1/2 w-1/2 h-1/2 bg-yellow-300/20 rounded-full blur-[90px]"></div>
</div>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-32">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shadow-lg shadow-zinc-900/20">
                <iconify-icon icon="solar:history-linear" class="text-2xl"></iconify-icon>
            </div>
            <h1 class="text-2xl font-bold text-zinc-900">Riwayat Pesanan</h1>
        </div>
        
        <a href="{{ route('user.home') }}" class="inline-flex flex-shrink-0 items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-zinc-200 text-zinc-900 font-medium hover:bg-zinc-50 hover:border-zinc-300 transition-all text-decoration-none shadow-sm">
            <iconify-icon icon="solar:cart-plus-linear" class="text-lg"></iconify-icon>
            Pesan Lagi
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white/60 backdrop-blur-xl border border-white/80 rounded-2xl p-4 mb-8 shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative min-w-[160px] flex-grow sm:flex-grow-0">
                <select name="status" class="appearance-none w-full h-11 pl-4 pr-10 rounded-xl bg-zinc-50 border border-zinc-200 text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-300 cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Menunggu</option>
                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Sedang Disiapkan</option>
                    <option value="ready"     {{ request('status') == 'ready'     ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <iconify-icon icon="solar:alt-arrow-down-linear" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none"></iconify-icon>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none h-11 px-5 rounded-xl bg-zinc-900 text-white text-sm font-medium hover:bg-zinc-800 transition-colors shadow-sm">Filter</button>
                <a href="{{ route('user.orders.history') }}" class="flex-1 sm:flex-none h-11 px-5 flex items-center justify-center rounded-xl bg-zinc-100 text-zinc-700 text-sm font-medium hover:bg-zinc-200 transition-colors text-decoration-none">Reset</a>
            </div>
        </form>
    </div>

    <div class="space-y-4">
        @forelse($orders as $order)
        @php
            $statusColors = [
                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                'preparing' => 'bg-blue-100 text-blue-700 border-blue-200',
                'ready' => 'bg-green-100 text-green-700 border-green-200',
                'completed' => 'bg-zinc-100 text-zinc-700 border-zinc-200',
                'cancelled' => 'bg-red-100 text-red-700 border-red-200',
            ];
            $colorClass = $statusColors[$order->status] ?? 'bg-zinc-100 text-zinc-700';
            
            $statusIcons = [
                'pending' => 'solar:clock-circle-linear',
                'preparing' => 'line-md:loading-loop',
                'ready' => 'solar:bell-bing-bold-duotone',
                'completed' => 'solar:check-circle-linear',
                'cancelled' => 'solar:close-circle-linear',
            ];
            $iconTag = $statusIcons[$order->status] ?? 'solar:info-circle-linear';
        @endphp

        <div class="bg-white/60 backdrop-blur-xl border border-white/80 rounded-3xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start gap-4 mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <iconify-icon icon="solar:hashtag-linear" class="text-zinc-400"></iconify-icon>
                        <span class="font-bold text-zinc-900">{{ $order->order_code }}</span>
                    </div>
                    <div class="text-xs text-zinc-500 flex items-center gap-1.5">
                        <iconify-icon icon="solar:calendar-date-linear"></iconify-icon>
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
                
                <div class="flex flex-col items-end gap-1.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $colorClass }}">
                        <iconify-icon icon="{{ $iconTag }}" class="text-sm"></iconify-icon>
                        {{ $order->status_label }}
                    </span>
                    <span class="text-xs font-medium text-zinc-500 bg-zinc-100 px-2 py-0.5 rounded-md">
                        {{ $order->break_time_label }}
                    </span>
                </div>
            </div>

            <!-- Items preview -->
            <div class="border-t border-zinc-100 pt-3 pb-4 mb-4">
                <div class="space-y-2">
                    @foreach($order->items->take(3) as $item)
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2 text-zinc-700">
                            <span class="w-6 h-6 rounded bg-zinc-100 flex items-center justify-center text-xs font-bold text-zinc-500">{{ $item->quantity }}x</span>
                            <span class="truncate max-w-[150px] sm:max-w-xs">{{ $item->menu->name ?? 'Menu dihapus' }}</span>
                        </div>
                        <span class="text-zinc-500 font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                    @if($order->items->count() > 3)
                    <div class="text-xs font-medium text-zinc-400 pl-8 pt-1">
                        + {{ $order->items->count() - 3 }} menu lainnya
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-between items-center bg-zinc-50/50 rounded-2xl p-4">
                <div>
                    <span class="block text-xs text-zinc-500 mb-0.5">Total Belanja</span>
                    <span class="font-bold text-lg text-zinc-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('user.orders.show', $order) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-zinc-200 text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-zinc-900 transition-colors shadow-sm text-decoration-none">
                    <iconify-icon icon="solar:document-text-linear"></iconify-icon>
                    Detail
                </a>
            </div>

            @if($order->status === 'ready')
            <div class="mt-4 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0">
                    <iconify-icon icon="solar:bell-ringing-bold-duotone" class="text-lg animate-pulse"></iconify-icon>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-green-800 mb-0.5">Pesananmu Siap Diambil!</h4>
                    <p class="text-sm text-green-700">Segera ambil pesanan kamu di kantin saat {{ $order->break_time_label }}.</p>
                </div>
            </div>
            @endif
        </div>
        @empty
        <div class="bg-white/60 backdrop-blur-xl border border-white/80 rounded-3xl p-12 text-center shadow-sm flex flex-col items-center">
            <div class="w-24 h-24 rounded-full bg-zinc-100 flex items-center justify-center mb-6">
                <iconify-icon icon="solar:document-text-linear" class="text-5xl text-zinc-300"></iconify-icon>
            </div>
            <h3 class="text-xl font-semibold text-zinc-900 mb-2">Belum ada riwayat pesanan</h3>
            <p class="text-zinc-500 mb-8 max-w-sm">Kamu belum pernah memesan makanan. Yuk mulai pesan menu favoritmu!</p>
            <a href="{{ route('user.home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-zinc-900 text-white font-medium hover:bg-zinc-800 hover:-translate-y-0.5 transition-all duration-200 text-decoration-none shadow-[0_0_20px_rgba(0,0,0,0.1)]">
                <iconify-icon icon="solar:shop-linear" class="text-lg"></iconify-icon>
                Mulai Pesan
            </a>
        </div>
        @endforelse

        @if($orders->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</main>
@endsection
