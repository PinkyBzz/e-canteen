@extends('layouts.warung')
@section('title', 'Laporan Pendapatan')
@section('page-title', 'Laporan Pendapatan')

@section('content')
{{-- Filter --}}
<div class="flex flex-wrap gap-3 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1">Tanggal</label>
            <input type="date" name="date" value="{{ $date->toDateString() }}"
                class="rounded-xl border border-zinc-200 bg-white/70 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 transition">
        </div>
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1">Bulan</label>
            <input type="month" name="month" value="{{ $month->format('Y-m') }}"
                class="rounded-xl border border-zinc-200 bg-white/70 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 transition">
        </div>
        <button type="submit" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
            <iconify-icon icon="solar:magnifer-linear"></iconify-icon>Filter
        </button>
    </form>
</div>

{{-- Summary cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-5">
        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-2">Pendapatan Harian</p>
        <p class="text-2xl font-bold text-zinc-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <p class="text-xs text-zinc-400 mt-1">{{ $date->isoFormat('D MMMM YYYY') }}</p>
    </div>
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-5">
        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-2">Pesanan Selesai</p>
        <p class="text-2xl font-bold text-zinc-900">{{ $totalOrders }}</p>
        <p class="text-xs text-zinc-400 mt-1">hari ini</p>
    </div>
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-5">
        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-2">Pendapatan Bulanan</p>
        <p class="text-2xl font-bold text-zinc-900">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
        <p class="text-xs text-zinc-400 mt-1">{{ $month->isoFormat('MMMM YYYY') }}</p>
    </div>
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-5">
        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-2">Pesanan Bulan Ini</p>
        <p class="text-2xl font-bold text-zinc-900">{{ $monthlyOrderCount }}</p>
        <p class="text-xs text-zinc-400 mt-1">pesanan selesai</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
    {{-- Chart --}}
    <div class="lg:col-span-2 bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-5">
        <p class="text-sm font-semibold text-zinc-800 mb-4">Pendapatan per Hari — {{ $month->isoFormat('MMMM YYYY') }}</p>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    {{-- Category revenue --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-5">
        <p class="text-sm font-semibold text-zinc-800 mb-4">Pendapatan per Kategori</p>
        @php $maxCat = $categoryRevenue->max() ?: 1; @endphp
        @forelse($categoryRevenue as $cat => $rev)
        <div class="mb-3">
            <div class="flex justify-between text-xs mb-1">
                <span class="font-medium text-zinc-700">{{ $cat }}</span>
                <span class="text-zinc-500">Rp {{ number_format($rev, 0, ',', '.') }}</span>
            </div>
            <div class="h-2 bg-zinc-100 rounded-full overflow-hidden">
                <div class="h-full bg-amber-400 rounded-full transition-all" style="width:{{ round($rev/$maxCat*100) }}%"></div>
            </div>
        </div>
        @empty
        <p class="text-sm text-zinc-400">Belum ada data</p>
        @endforelse
    </div>
</div>

{{-- Top items --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-zinc-100">
            <p class="text-sm font-semibold text-zinc-800">Menu Terlaris Hari Ini</p>
        </div>
        <table class="w-full text-sm">
            <thead><tr class="bg-zinc-50/80 text-zinc-500 text-xs uppercase">
                <th class="px-5 py-2.5 text-left font-medium">Menu</th>
                <th class="px-4 py-2.5 text-center font-medium">Qty</th>
                <th class="px-5 py-2.5 text-right font-medium">Revenue</th>
            </tr></thead>
            <tbody class="divide-y divide-zinc-50">
                @forelse($topItems as $item)
                <tr class="hover:bg-zinc-50/50 transition">
                    <td class="px-5 py-2.5 text-zinc-800 font-medium">{{ $item->menu->name ?? '—' }}</td>
                    <td class="px-4 py-2.5 text-center text-zinc-600">{{ $item->total_qty }}x</td>
                    <td class="px-5 py-2.5 text-right text-zinc-700">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-5 py-6 text-center text-zinc-400 text-sm">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Orders list --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-zinc-100">
            <p class="text-sm font-semibold text-zinc-800">Pesanan Selesai Hari Ini</p>
        </div>
        <div class="divide-y divide-zinc-50 max-h-72 overflow-y-auto">
            @forelse($orders as $order)
            <div class="flex items-center justify-between px-5 py-2.5">
                <div>
                    <p class="text-xs font-mono font-semibold text-zinc-700">{{ $order->order_code }}</p>
                    <p class="text-xs text-zinc-400">{{ $order->user->name }} · {{ $order->break_time_label }}</p>
                </div>
                <p class="text-sm font-semibold text-emerald-600">Rp {{ number_format($order->items->sum(fn($i)=>$i->price*$i->quantity), 0, ',', '.') }}</p>
            </div>
            @empty
            <div class="px-5 py-6 text-center text-zinc-400 text-sm">Belum ada pesanan selesai</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($dailyRevenue, 'day')) !!},
        datasets: [{
            label: 'Pendapatan',
            data: {!! json_encode(array_column($dailyRevenue, 'revenue')) !!},
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245,158,11,0.08)',
            borderWidth: 2.5,
            pointStyle: 'circle',
            pointRadius: 3,
            tension: .4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { color: '#a1a1aa', font: { size: 11 } } },
            y: {
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: { color: '#a1a1aa', font: { size: 11 }, callback: v => 'Rp ' + (v >= 1000 ? (v/1000).toFixed(0)+'rb' : v) }
            }
        }
    }
});
</script>
@endpush
