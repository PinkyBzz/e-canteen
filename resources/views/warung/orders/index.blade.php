@extends('layouts.warung')
@section('title', 'Antrean Pesanan')
@section('page-title', 'Antrean Pesanan')

@section('content')
{{-- Status summary --}}
<div class="grid grid-cols-3 gap-2 sm:gap-4 mb-5">
    @foreach([['pending','Menunggu','amber','solar:clock-circle-linear'],['preparing','Diproses','blue','solar:chef-hat-linear'],['ready','Siap Ambil','emerald','solar:check-circle-linear']] as [$st,$lb,$cl,$ic])
    <a href="{{ route('warung.orders.index', ['status'=>$st]) }}"
        class="bg-white/70 backdrop-blur-xl border {{ request('status')===$st ? 'border-'.$cl.'-300' : 'border-white/80' }} rounded-2xl sm:rounded-3xl shadow-sm p-3 sm:p-4 hover:shadow-md transition" style="text-decoration:none">
        <div class="flex items-center gap-1.5 mb-1 sm:mb-2">
            <iconify-icon icon="{{ $ic }}" class="text-{{ $cl }}-500 text-base sm:text-lg"></iconify-icon>
            <span class="text-[10px] sm:text-xs font-semibold text-zinc-500 leading-tight">{{ $lb }}</span>
        </div>
        <p class="text-2xl sm:text-3xl font-bold text-zinc-900">{{ $statusCounts[$st] }}</p>
    </a>
    @endforeach
</div>

{{-- Filters --}}
<div class="flex flex-wrap gap-2 mb-4">
    <form method="GET" class="flex flex-wrap gap-2">
        <a href="{{ route('warung.orders.index') }}" class="text-xs px-3 py-1.5 rounded-xl border font-medium transition {{ !request('status') ? 'bg-amber-500 text-white border-amber-500 shadow-sm' : 'bg-white/70 border-zinc-200 text-zinc-600 hover:bg-white' }}">Semua</a>
        @foreach(['pending'=>'Menunggu','preparing'=>'Diproses','ready'=>'Siap Ambil','completed'=>'Selesai','cancelled'=>'Batal'] as $val=>$label)
        <a href="{{ route('warung.orders.index', ['status'=>$val]) }}" class="text-xs px-3 py-1.5 rounded-xl border font-medium transition {{ request('status')===$val ? 'bg-amber-500 text-white border-amber-500 shadow-sm' : 'bg-white/70 border-zinc-200 text-zinc-600 hover:bg-white' }}">{{ $label }}</a>
        @endforeach
        <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
            class="text-xs rounded-xl border border-zinc-200 bg-white/70 px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-amber-400/30 transition">
    </form>
</div>

{{-- Mobile: Cards | Desktop: Table --}}
@forelse($orders as $order)
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

{{-- Mobile Card --}}
<div class="sm:hidden bg-white/80 backdrop-blur-xl border border-white/90 rounded-2xl shadow-sm p-4 mb-3">
    <div class="flex items-start justify-between mb-3">
        <div>
            <a href="{{ route('warung.orders.show', $order) }}" class="font-mono text-sm font-bold text-zinc-800">{{ $order->order_code }}</a>
            <p class="text-xs text-zinc-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc }}">{{ $order->status_label }}</span>
    </div>
    <div class="flex items-center gap-2 mb-3">
        <iconify-icon icon="solar:user-linear" class="text-zinc-400 text-sm flex-shrink-0"></iconify-icon>
        <span class="text-sm text-zinc-700 font-medium">{{ $order->user->name }}</span>
        <span class="ml-auto text-xs bg-indigo-50 text-indigo-700 font-semibold px-2 py-0.5 rounded-full">{{ $order->break_time_label }}</span>
    </div>
    <div class="flex flex-wrap gap-1 mb-3">
        @foreach($order->items->take(4) as $item)
        <span class="text-xs bg-zinc-100 text-zinc-600 px-2 py-0.5 rounded-lg">{{ $item->menu->name ?? '—' }} ×{{ $item->quantity }}</span>
        @endforeach
        @if($order->items->count() > 4)<span class="text-xs text-zinc-400">+{{ $order->items->count()-4 }} lainnya</span>@endif
    </div>
    @if(!in_array($order->status, ['completed','cancelled']))
    <form method="POST" action="{{ route('warung.orders.update-status', $order) }}" class="flex items-center gap-2">
        @csrf @method('PATCH')
        <select name="status" onchange="this.form.submit()"
            class="flex-1 text-sm rounded-xl border border-zinc-200 bg-white/80 px-3 py-2 focus:outline-none transition">
            @foreach(['pending'=>'Menunggu','preparing'=>'Diproses','ready'=>'Siap Ambil','completed'=>'Selesai','cancelled'=>'Batal'] as $val=>$lbl)
            <option value="{{ $val }}" {{ $order->status===$val?'selected':'' }}>{{ $lbl }}</option>
            @endforeach
        </select>
        <a href="{{ route('warung.orders.show', $order) }}" class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center text-zinc-500 flex-shrink-0" style="text-decoration:none">
            <iconify-icon icon="solar:eye-linear" class="text-base"></iconify-icon>
        </a>
    </form>
    @else
    <a href="{{ route('warung.orders.show', $order) }}" class="flex items-center justify-center gap-2 w-full py-2 rounded-xl bg-zinc-50 text-zinc-500 text-sm" style="text-decoration:none">
        <iconify-icon icon="solar:eye-linear"></iconify-icon> Lihat Detail
    </a>
    @endif
</div>
@empty
<div class="sm:hidden bg-white/70 backdrop-blur-xl border border-white/80 rounded-2xl p-10 text-center text-zinc-400 mb-3">
    Tidak ada pesanan ditemukan
</div>
@endforelse

{{-- Desktop Table --}}
<div class="hidden sm:block bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-zinc-50/80 text-zinc-500 text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-medium">Kode</th>
                    <th class="px-4 py-3 text-left font-medium">Pemesan</th>
                    <th class="px-4 py-3 text-left font-medium">Item Saya</th>
                    <th class="px-4 py-3 text-center font-medium">Waktu</th>
                    <th class="px-4 py-3 text-center font-medium">Status</th>
                    <th class="px-5 py-3 text-right font-medium">Update</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @forelse($orders as $order)
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
                <tr class="hover:bg-zinc-50/50 transition">
                    <td class="px-5 py-3">
                        <a href="{{ route('warung.orders.show', $order) }}" class="font-mono text-xs font-semibold text-zinc-700 hover:text-amber-600 transition">{{ $order->order_code }}</a>
                        <p class="text-xs text-zinc-400">{{ $order->created_at->format('d/m H:i') }}</p>
                    </td>
                    <td class="px-4 py-3 text-zinc-700 text-sm">{{ $order->user->name }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @foreach($order->items->take(3) as $item)
                            <span class="text-xs bg-zinc-100 text-zinc-600 px-2 py-0.5 rounded-lg">{{ $item->menu->name ?? '—' }} ×{{ $item->quantity }}</span>
                            @endforeach
                            @if($order->items->count() > 3)<span class="text-xs text-zinc-400">+{{ $order->items->count()-3 }}</span>@endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center"><span class="text-xs text-zinc-600">{{ $order->break_time_label }}</span></td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $sc }}">{{ $order->status_label }}</span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        @if(!in_array($order->status, ['completed','cancelled']))
                        <form method="POST" action="{{ route('warung.orders.update-status', $order) }}" class="flex items-center justify-end gap-1">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                class="text-xs rounded-xl border border-zinc-200 bg-white/60 px-2 py-1.5 focus:outline-none transition">
                                @foreach(['pending'=>'Menunggu','preparing'=>'Diproses','ready'=>'Siap Ambil','completed'=>'Selesai','cancelled'=>'Batal'] as $val=>$lbl)
                                <option value="{{ $val }}" {{ $order->status===$val?'selected':'' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </form>
                        @else
                        <span class="text-xs text-zinc-400">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-zinc-400">Tidak ada pesanan ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-5 py-4 border-t border-zinc-100">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
