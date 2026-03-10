@extends('layouts.admin')
@section('title', 'Antrean Pesanan')
@section('page-title', 'Antrean Pesanan')

@section('content')
{{-- Status Summary --}}
<div class="grid grid-cols-3 gap-2 sm:gap-4 mb-6">
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm text-center">
        <div class="text-3xl font-extrabold text-amber-600">{{ $pendingCount }}</div>
        <div class="text-xs font-bold text-zinc-400 mt-1 uppercase tracking-wider">Menunggu</div>
    </div>
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm text-center">
        <div class="text-3xl font-extrabold text-blue-600">{{ $preparingCount }}</div>
        <div class="text-xs font-bold text-zinc-400 mt-1 uppercase tracking-wider">Diproses</div>
    </div>
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm text-center">
        <div class="text-3xl font-extrabold text-emerald-600">{{ $readyCount }}</div>
        <div class="text-xs font-bold text-zinc-400 mt-1 uppercase tracking-wider">Siap Ambil</div>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-4 sm:p-5 shadow-sm mb-5">
    <form method="GET" class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-3 sm:items-end">
        <input type="date" name="date" value="{{ request('date', today()->format('Y-m-d')) }}"
            class="col-span-2 sm:col-auto h-10 px-4 rounded-2xl border border-zinc-200 bg-white/80 text-sm text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-200 transition-all">
        <select name="status" class="h-10 px-3 rounded-2xl border border-zinc-200 bg-white/80 text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-200">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Diproses</option>
            <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap Ambil</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <select name="break_time" class="h-10 px-3 rounded-2xl border border-zinc-200 bg-white/80 text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-200">
            <option value="">Semua Istirahat</option>
            <option value="istirahat_1" {{ request('break_time') == 'istirahat_1' ? 'selected' : '' }}>Istirahat 1</option>
            <option value="istirahat_2" {{ request('break_time') == 'istirahat_2' ? 'selected' : '' }}>Istirahat 2</option>
        </select>
        <button type="submit" class="col-span-2 sm:col-auto h-10 px-5 rounded-2xl bg-zinc-900 text-sm font-bold hover:bg-zinc-700 transition-colors" style="color:white">Filter</button>
    </form>
</div>

{{-- Mobile Cards --}}
<div class="sm:hidden space-y-3 mb-4">
    @forelse($orders as $order)
    <div class="bg-white/80 backdrop-blur-xl border border-white/90 rounded-2xl shadow-sm p-4">
        <div class="flex items-start justify-between mb-2">
            <div>
                <span class="font-mono text-sm font-bold text-zinc-800">{{ $order->order_code }}</span>
                <p class="text-xs text-zinc-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full
                {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' :
                   ($order->status === 'preparing' ? 'bg-blue-100 text-blue-700' :
                   ($order->status === 'ready' ? 'bg-emerald-100 text-emerald-700' :
                   ($order->status === 'completed' ? 'bg-zinc-100 text-zinc-500' : 'bg-red-100 text-red-600'))) }}">
                {{ $order->status_label }}
            </span>
        </div>
        <div class="flex items-center justify-between mb-2">
            <div>
                <p class="text-sm font-semibold text-zinc-900">{{ $order->user->name }}</p>
                <p class="text-xs text-zinc-400">{{ $order->user->email }}</p>
            </div>
            <span class="text-xs bg-indigo-50 text-indigo-700 font-semibold px-2 py-0.5 rounded-full">{{ $order->break_time_label }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="font-bold text-zinc-900 text-sm">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            <div class="flex items-center gap-2">
                @if(Route::has('admin.orders.show'))
                <a href="{{ route('admin.orders.show', $order) }}" class="h-9 px-4 rounded-xl bg-zinc-100 flex items-center justify-center gap-1.5 text-zinc-600 text-xs font-semibold hover:bg-zinc-200 transition-colors" style="text-decoration:none">
                    <iconify-icon icon="solar:eye-linear" class="text-sm"></iconify-icon>
                    <span>Lihat</span>
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white/70 border border-white/80 rounded-2xl p-10 text-center text-zinc-400">Tidak ada pesanan</div>
    @endforelse
    @if($orders->hasPages())
    <div class="pt-2">{{ $orders->links() }}</div>
    @endif
</div>

{{-- Desktop Table --}}
<div class="hidden sm:block bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-zinc-100">
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Kode</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Pemesan</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Waktu Ambil</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Total</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Status</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Waktu</th>
                <th class="text-center px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-50">
            @forelse($orders as $order)
            <tr class="hover:bg-zinc-50/60 transition-colors">
                <td class="px-6 py-4 font-mono text-xs font-semibold text-zinc-700">{{ $order->order_code }}</td>
                <td class="px-6 py-4">
                    <div class="font-semibold text-zinc-900">{{ $order->user->name }}</div>
                    <div class="text-xs text-zinc-400">{{ $order->user->email }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full">{{ $order->break_time_label }}</span>
                </td>
                <td class="px-6 py-4 font-bold text-zinc-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold
                        {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' :
                           ($order->status === 'preparing' ? 'bg-blue-100 text-blue-700' :
                           ($order->status === 'ready' ? 'bg-emerald-100 text-emerald-700' :
                           ($order->status === 'completed' ? 'bg-zinc-100 text-zinc-500' : 'bg-red-100 text-red-600'))) }}">
                        {{ $order->status_label }}
                    </span>
                </td>
                <td class="px-6 py-4 text-xs text-zinc-400 font-medium">{{ $order->created_at->format('H:i') }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        @if(Route::has('admin.orders.show'))
                        <a href="{{ route('admin.orders.show', $order) }}"
                            class="h-9 px-4 rounded-2xl bg-zinc-100 flex items-center justify-center gap-1.5 text-zinc-600 text-xs font-semibold hover:bg-zinc-200 transition-colors" style="text-decoration:none" title="Lihat Detail">
                            <iconify-icon icon="solar:eye-linear" class="text-sm"></iconify-icon>
                            <span>Lihat</span>
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-16 text-center">
                    <iconify-icon icon="solar:inbox-linear" class="text-5xl text-zinc-300 block mb-3"></iconify-icon>
                    <p class="text-zinc-400 font-medium">Tidak ada pesanan untuk filter yang dipilih</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-zinc-100">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
