@extends('layouts.admin')
@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">

    {{-- Order card --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">

        {{-- Header bar --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-zinc-900 flex items-center justify-center">
                    <iconify-icon icon="solar:receipt-linear" class="text-white"></iconify-icon>
                </div>
                <span class="font-semibold text-zinc-900 tracking-wide">{{ $order->order_code }}</span>
            </div>
            @php
                $statusColor = match($order->status) {
                    'pending'   => 'bg-amber-100 text-amber-700',
                    'preparing' => 'bg-blue-100 text-blue-700',
                    'ready'     => 'bg-emerald-100 text-emerald-700',
                    'completed' => 'bg-zinc-100 text-zinc-600',
                    'cancelled' => 'bg-red-100 text-red-600',
                    default     => 'bg-zinc-100 text-zinc-600',
                };
            @endphp
            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusColor }}">{{ $order->status_label }}</span>
        </div>

        {{-- Meta info --}}
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

        {{-- Items table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-zinc-50/80 text-zinc-500 text-xs uppercase tracking-wide">
                        <th class="px-6 py-3 text-left font-medium">Menu</th>
                        <th class="px-4 py-3 text-center font-medium">Qty</th>
                        <th class="px-4 py-3 text-right font-medium">Harga</th>
                        <th class="px-6 py-3 text-right font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @foreach($order->items as $item)
                    <tr class="hover:bg-zinc-50/50 transition">
                        <td class="px-6 py-3 text-zinc-800">{{ $item->menu->name ?? 'Menu dihapus' }}</td>
                        <td class="px-4 py-3 text-center text-zinc-600">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-right text-zinc-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right font-semibold text-zinc-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-zinc-50/80 border-t border-zinc-100">
                        <td colspan="3" class="px-6 py-3 text-right font-semibold text-zinc-700 text-sm">Total</td>
                        <td class="px-6 py-3 text-right font-bold text-emerald-600 text-base">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Update status --}}
        @if(!in_array($order->status, ['completed', 'cancelled']))
        <div class="px-6 py-4 border-t border-zinc-100">
            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="flex items-center gap-3">
                @csrf @method('PATCH')
                <select name="status"
                    class="rounded-xl border border-zinc-200 bg-white/60 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/20 focus:border-zinc-400 transition">
                    <option value="pending"   {{ $order->status == 'pending'   ? 'selected' : '' }}>Menunggu</option>
                    <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Sedang Disiapkan</option>
                    <option value="ready"     {{ $order->status == 'ready'     ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit"
                    class="flex items-center gap-2 bg-zinc-900 hover:bg-zinc-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
                    <iconify-icon icon="solar:refresh-linear"></iconify-icon>
                    Update Status
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Back --}}
    <a href="{{ route('admin.orders.index') }}"
        class="inline-flex items-center gap-2 text-sm text-zinc-600 hover:text-zinc-900 transition font-medium">
        <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>
        Kembali ke Daftar Pesanan
    </a>
</div>
@endsection
