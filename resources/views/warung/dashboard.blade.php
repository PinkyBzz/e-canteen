@extends('layouts.warung')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Warung')

@section('content')
{{-- Stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Total Menu</span>
            <div class="w-9 h-9 rounded-2xl bg-amber-50 flex items-center justify-center">
                <iconify-icon icon="solar:hamburger-menu-linear" class="text-amber-500 text-lg"></iconify-icon>
            </div>
        </div>
        <p class="text-3xl font-bold text-zinc-900">{{ $totalMenus }}</p>
        <p class="text-xs text-zinc-400 mt-1">{{ $availableMenus }} tersedia</p>
    </div>
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Pesanan Hari Ini</span>
            <div class="w-9 h-9 rounded-2xl bg-blue-50 flex items-center justify-center">
                <iconify-icon icon="solar:bill-list-linear" class="text-blue-500 text-lg"></iconify-icon>
            </div>
        </div>
        <p class="text-3xl font-bold text-zinc-900">{{ $todayOrders }}</p>
        <p class="text-xs text-zinc-400 mt-1">{{ $pendingOrders }} menunggu proses</p>
    </div>
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Pendapatan Hari Ini</span>
            <div class="w-9 h-9 rounded-2xl bg-emerald-50 flex items-center justify-center">
                <iconify-icon icon="solar:wallet-money-linear" class="text-emerald-500 text-lg"></iconify-icon>
            </div>
        </div>
        <p class="text-2xl font-bold text-zinc-900">Rp {{ number_format($revenueToday, 0, ',', '.') }}</p>
        <p class="text-xs text-zinc-400 mt-1">dari pesanan selesai</p>
    </div>
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Pendapatan Bulan Ini</span>
            <div class="w-9 h-9 rounded-2xl bg-violet-50 flex items-center justify-center">
                <iconify-icon icon="solar:chart-2-linear" class="text-violet-500 text-lg"></iconify-icon>
            </div>
        </div>
        <p class="text-2xl font-bold text-zinc-900">Rp {{ number_format($revenueMonth, 0, ',', '.') }}</p>
        <p class="text-xs text-zinc-400 mt-1">{{ now()->isoFormat('MMMM YYYY') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-5">
    {{-- Recent Orders --}}
    <div class="lg:col-span-3 bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-100">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:clock-circle-linear" class="text-amber-500 text-lg"></iconify-icon>
                <span class="font-semibold text-zinc-900 text-sm">Pesanan Terbaru</span>
            </div>
            <a href="{{ route('warung.orders.index') }}" class="text-xs text-zinc-400 hover:text-zinc-700 transition">Lihat semua →</a>
        </div>
        <div class="divide-y divide-zinc-50">
            @forelse($recentOrders as $order)
            @php
                $sc = match($order->status) {
                    'pending'   => 'bg-amber-100 text-amber-700',
                    'preparing' => 'bg-blue-100 text-blue-700',
                    'ready'     => 'bg-emerald-100 text-emerald-700',
                    'completed' => 'bg-zinc-100 text-zinc-500',
                    'cancelled' => 'bg-red-100 text-red-600',
                    default     => 'bg-zinc-100 text-zinc-500',
                };
            @endphp
            <a href="{{ route('warung.orders.show', $order) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-zinc-50/50 transition" style="text-decoration:none">
                <div class="w-8 h-8 rounded-xl bg-zinc-100 flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="solar:receipt-linear" class="text-zinc-500 text-sm"></iconify-icon>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-zinc-800 truncate">{{ $order->user->name }}</p>
                    <p class="text-xs text-zinc-400">{{ $order->order_code }} · {{ $order->break_time_label }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $sc }}">{{ $order->status_label }}</span>
                    <p class="text-xs text-zinc-400 mt-0.5">{{ $order->created_at->diffForHumans() }}</p>
                </div>
            </a>
            @empty
            <div class="px-5 py-8 text-center text-zinc-400 text-sm">Belum ada pesanan hari ini</div>
            @endforelse
        </div>
    </div>

    {{-- Top Selling --}}
    <div class="lg:col-span-2 bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
        <div class="flex items-center gap-2 px-5 py-4 border-b border-zinc-100">
            <iconify-icon icon="solar:star-linear" class="text-amber-500 text-lg"></iconify-icon>
            <span class="font-semibold text-zinc-900 text-sm">Menu Terlaris Bulan Ini</span>
        </div>
        <div class="divide-y divide-zinc-50">
            @forelse($topMenus as $i => $item)
            <div class="flex items-center gap-3 px-5 py-3">
                <span class="w-6 h-6 rounded-lg bg-{{ $i === 0 ? 'amber' : ($i === 1 ? 'zinc' : 'stone') }}-100 text-{{ $i === 0 ? 'amber' : ($i === 1 ? 'zinc' : 'stone') }}-600 text-xs font-bold flex items-center justify-center flex-shrink-0">{{ $i+1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-zinc-800 truncate">{{ $item->menu->name ?? '-' }}</p>
                    <p class="text-xs text-zinc-400">{{ $item->total_qty }}x terjual</p>
                </div>
                <span class="text-xs font-semibold text-emerald-600 flex-shrink-0">Rp {{ number_format($item->total_rev, 0, ',', '.') }}</span>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-zinc-400 text-sm">Belum ada data</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
