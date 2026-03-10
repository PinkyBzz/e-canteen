@extends('layouts.admin')
@section('title', 'Laporan Harian')
@section('page-title', 'Laporan Harian')

@section('content')
{{-- Filter --}}
<div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm mb-6">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-zinc-500 mb-1.5 uppercase tracking-wider">Laporan Harian</label>
            <input type="date" name="date" value="{{ $date->format('Y-m-d') }}"
                class="h-10 px-4 rounded-2xl border border-zinc-200 bg-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-200 transition-all">
        </div>
        <div>
            <label class="block text-xs font-bold text-zinc-500 mb-1.5 uppercase tracking-wider">Bulan Rekap</label>
            <input type="month" name="month" value="{{ $month->format('Y-m') }}"
                class="h-10 px-4 rounded-2xl border border-zinc-200 bg-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-200 transition-all">
        </div>
        <button type="submit" class="h-10 px-5 rounded-2xl bg-zinc-900 text-white text-sm font-bold hover:bg-zinc-700 transition-colors self-end">Lihat Laporan</button>
    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm text-center">
        <div class="text-3xl font-extrabold text-zinc-900">{{ $totalOrders }}</div>
        <div class="text-xs font-semibold text-zinc-400 mt-1">Pesanan {{ $date->format('d/m') }}</div>
    </div>
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm text-center">
        <div class="text-xl font-extrabold text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div class="text-xs font-semibold text-zinc-400 mt-1">Pendapatan {{ $date->format('d/m') }}</div>
    </div>
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm text-center">
        <div class="text-3xl font-extrabold text-zinc-900">{{ $monthlyOrderCount }}</div>
        <div class="text-xs font-semibold text-zinc-400 mt-1">Pesanan {{ $month->format('M Y') }}</div>
    </div>
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm text-center">
        <div class="text-xl font-extrabold text-indigo-600">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</div>
        <div class="text-xs font-semibold text-zinc-400 mt-1">Pendapatan {{ $month->format('M Y') }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Monthly Chart --}}
    <div class="lg:col-span-2 bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-100">
            <h3 class="font-bold text-zinc-900 text-sm">Pendapatan Harian — {{ $month->format('F Y') }}</h3>
        </div>
        <div class="p-6">
            <canvas id="monthChart" height="110"></canvas>
        </div>
    </div>

    {{-- Category Revenue --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-100">
            <h3 class="font-bold text-zinc-900 text-sm">Revenue per Kategori ({{ $date->format('d/m') }})</h3>
        </div>
        <div class="p-6 space-y-4">
            @forelse($categoryRevenue as $cat => $rev)
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-sm font-semibold text-zinc-700">{{ $cat }}</span>
                    <span class="text-xs font-bold text-zinc-500">Rp {{ number_format($rev, 0, ',', '.') }}</span>
                </div>
                <div class="w-full bg-zinc-100 rounded-full h-2">
                    <div class="bg-zinc-900 h-2 rounded-full transition-all duration-500"
                        style="width:{{ $totalRevenue > 0 ? round(($rev/$totalRevenue)*100) : 0 }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-center text-zinc-400 text-sm py-4">Tidak ada data untuk tanggal ini</p>
            @endforelse
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Top Items --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-100">
            <h3 class="font-bold text-zinc-900 text-sm">Menu Terlaris ({{ $date->format('d M Y') }})</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-50">
                    <th class="text-left px-6 py-3 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Menu</th>
                    <th class="text-center px-6 py-3 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Porsi</th>
                    <th class="text-right px-6 py-3 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @forelse($topItems as $item)
                <tr class="hover:bg-zinc-50/60">
                    <td class="px-6 py-3 font-medium text-zinc-800">{{ $item->menu->name ?? '-' }}</td>
                    <td class="px-6 py-3 text-center font-bold text-zinc-900">{{ $item->total_qty }}</td>
                    <td class="px-6 py-3 text-right font-semibold text-zinc-700">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-8 text-center text-zinc-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Daily Orders --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-100">
            <h3 class="font-bold text-zinc-900 text-sm">Daftar Pesanan ({{ $date->format('d M Y') }})</h3>
        </div>
        <div class="overflow-y-auto max-h-72">
            <table class="w-full text-sm">
                <thead class="sticky top-0 bg-white/90 backdrop-blur-md">
                    <tr class="border-b border-zinc-100">
                        <th class="text-left px-6 py-3 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Pesanan</th>
                        <th class="text-left px-6 py-3 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Pemesan</th>
                        <th class="text-right px-6 py-3 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-zinc-50/60">
                        <td class="px-6 py-2.5 font-mono text-xs text-zinc-600">{{ $order->order_code }}</td>
                        <td class="px-6 py-2.5 text-zinc-700">{{ $order->user->name }}</td>
                        <td class="px-6 py-2.5 text-right font-semibold text-zinc-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-8 text-center text-zinc-400">Tidak ada pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('monthChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($dailyRevenue, 'day')) !!},
        datasets: [{
            label: 'Pendapatan',
            data: {!! json_encode(array_column($dailyRevenue, 'revenue')) !!},
            borderColor: '#18181b',
            backgroundColor: 'rgba(24,24,27,.06)',
            tension: .4, fill: true, pointRadius: 4,
            pointBackgroundColor: '#18181b',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.04)' }, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID'), font: { size: 10 } } },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
    }
});
</script>
@endpush
