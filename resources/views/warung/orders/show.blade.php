@extends('layouts.warung')
@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500 flex items-center justify-center">
                    <iconify-icon icon="solar:receipt-linear" class="text-white"></iconify-icon>
                </div>
                <span class="font-semibold text-zinc-900 tracking-wide">{{ $order->order_code }}</span>
            </div>
            @php
                $sc = match($order->status) {
                    'pending'   => 'bg-amber-100 text-amber-700',
                    'preparing' => 'bg-blue-100 text-blue-700',
                    'ready'     => 'bg-emerald-100 text-emerald-700',
                    'completed' => 'bg-zinc-100 text-zinc-600',
                    'cancelled' => 'bg-red-100 text-red-600',
                    default     => 'bg-zinc-100 text-zinc-600',
                };
            @endphp
            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $sc }}">{{ $order->status_label }}</span>
        </div>

        {{-- Meta --}}
        <div class="grid grid-cols-2 gap-4 px-6 py-5 border-b border-zinc-100">
            <div>
                <p class="text-xs text-zinc-400 mb-0.5">Pemesan</p>
                <p class="text-sm font-semibold text-zinc-900">{{ $order->user->name }}</p>
                <p class="text-xs text-zinc-500">{{ $order->user->email }}</p>
            </div>
            <div>
                <p class="text-xs text-zinc-400 mb-0.5">Waktu Pengambilan</p>
                <p class="text-sm font-semibold text-zinc-900">{{ $order->break_time_label }}</p>
            </div>
            <div>
                <p class="text-xs text-zinc-400 mb-0.5">Tanggal Pesan</p>
                <p class="text-sm text-zinc-700">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-xs text-zinc-400 mb-0.5">Catatan</p>
                <p class="text-sm text-zinc-700">{{ $order->notes ?: '-' }}</p>
            </div>
        </div>

        {{-- My items from this warung --}}
        <div class="px-6 py-4 border-b border-zinc-100">
            <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-3">Item dari Warung Anda</p>
            <div class="space-y-2">
                @foreach($myItems as $item)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($item->menu?->photo)
                        <img src="{{ asset('storage/'.$item->menu->photo) }}" class="w-9 h-9 rounded-xl object-cover border border-zinc-100">
                        @else
                        <div class="w-9 h-9 rounded-xl bg-zinc-100 flex items-center justify-center"><iconify-icon icon="solar:gallery-linear" class="text-zinc-400 text-sm"></iconify-icon></div>
                        @endif
                        <div>
                            <p class="text-sm font-semibold text-zinc-800">{{ $item->menu->name ?? 'Menu dihapus' }}</p>
                            <p class="text-xs text-zinc-400">{{ $item->quantity }}x × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <p class="font-semibold text-zinc-900 text-sm">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
            <div class="flex justify-between items-center mt-4 pt-3 border-t border-zinc-100">
                <span class="text-sm font-semibold text-zinc-600">Subtotal Warung Saya</span>
                <span class="text-base font-bold text-emerald-600">Rp {{ number_format($myItems->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Update status --}}
        @if(!in_array($order->status, ['completed','cancelled']))
        <div class="px-6 py-4">
            <form method="POST" action="{{ route('warung.orders.update-status', $order) }}" class="flex items-center gap-3">
                @csrf @method('PATCH')
                <select name="status" class="rounded-xl border border-zinc-200 bg-white/60 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition">
                    @foreach(['pending'=>'Menunggu','preparing'=>'Sedang Disiapkan','ready'=>'Siap Diambil','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $val=>$label)
                    <option value="{{ $val }}" {{ $order->status===$val?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
                    <iconify-icon icon="solar:refresh-linear"></iconify-icon>Update Status
                </button>
            </form>
        </div>
        @endif
    </div>

    <a href="{{ route('warung.orders.index') }}" class="inline-flex items-center gap-2 text-sm text-zinc-600 hover:text-zinc-900 transition font-medium">
        <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>Kembali ke Daftar Pesanan
    </a>
</div>
@endsection
