@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="solar:cup-hot-linear" class="text-2xl text-emerald-600"></iconify-icon>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-zinc-900">{{ $totalMenus }}</div>
                <div class="text-xs font-semibold text-zinc-400">Total Menu</div>
            </div>
        </div>
        <div class="mt-3 text-xs font-semibold text-emerald-600">● {{ $availableMenus }} tersedia</div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="solar:users-group-rounded-linear" class="text-2xl text-indigo-600"></iconify-icon>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-zinc-900">{{ $totalUsers }}</div>
                <div class="text-xs font-semibold text-zinc-400">Total User</div>
            </div>
        </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="solar:bill-list-linear" class="text-2xl text-amber-600"></iconify-icon>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-zinc-900">{{ $todayOrders }}</div>
                <div class="text-xs font-semibold text-zinc-400">Pesanan Hari Ini</div>
            </div>
        </div>
        @if($pendingOrders > 0)
        <div class="mt-3 text-xs font-semibold text-amber-600">⚠ {{ $pendingOrders }} perlu diproses</div>
        @endif
    </div>

    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="solar:wallet-money-linear" class="text-2xl text-emerald-600"></iconify-icon>
            </div>
            <div>
                <div class="text-xl font-extrabold text-zinc-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
                <div class="text-xs font-semibold text-zinc-400">Pendapatan Hari Ini</div>
            </div>
        </div>
    </div>
</div>

{{-- Top-up Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="sm:col-span-2 bg-gradient-to-br from-violet-50 to-indigo-50 border border-indigo-100 rounded-3xl p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-violet-100 flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="solar:wallet-bold-duotone" class="text-2xl text-violet-600"></iconify-icon>
                </div>
                <div>
                    <div class="text-xs font-bold text-violet-400 uppercase tracking-wider mb-0.5">Total Top-Up (All Time)</div>
                    <div class="text-2xl font-extrabold text-violet-900">Rp {{ number_format($totalTopup, 0, ',', '.') }}</div>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-violet-500 hover:text-violet-700 transition" style="text-decoration:none">Lihat Users →</a>
        </div>
        <div class="mt-4 pt-4 border-t border-violet-100 flex items-center justify-between">
            <div>
                <span class="text-xs text-violet-400">Hari ini</span>
                <p class="text-base font-bold text-violet-700">Rp {{ number_format($todayTopup, 0, ',', '.') }}</p>
            </div>
            <div class="text-right">
                <span class="text-xs text-violet-400">Total Transaksi</span>
                <p class="text-base font-bold text-violet-700">{{ $totalTopupCount }}×</p>
            </div>
        </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm flex flex-col justify-between">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="solar:graph-new-up-linear" class="text-2xl text-amber-500"></iconify-icon>
            </div>
            <div>
                <div class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-0.5">Rata-rata Top-Up</div>
                <div class="text-xl font-extrabold text-zinc-900">
                    Rp {{ $totalTopupCount > 0 ? number_format($totalTopup / $totalTopupCount, 0, ',', '.') : '0' }}
                </div>
            </div>
        </div>
        <div class="text-xs text-zinc-400">per transaksi dari {{ $totalTopupCount }} kali top-up</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Revenue Chart --}}
    <div class="lg:col-span-2 bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100">
            <h3 class="font-bold text-zinc-900 text-sm">Pendapatan 7 Hari Terakhir</h3>
        </div>
        <div class="p-6">
            <canvas id="revenueChart" height="120"></canvas>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100">
            <h3 class="font-bold text-zinc-900 text-sm">Pesanan Terbaru</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-zinc-400 hover:text-zinc-900 transition-colors" style="text-decoration:none">Lihat Semua</a>
        </div>
        <div class="divide-y divide-zinc-100">
            @forelse($recentOrders as $order)
            <div class="flex items-center justify-between px-6 py-3">
                <div>
                    <div class="text-sm font-semibold text-zinc-800">{{ $order->user->name }}</div>
                    <div class="text-xs text-zinc-400 font-mono">{{ $order->order_code }}</div>
                </div>
                <div class="text-right">
                    <span class="inline-block text-[10px] font-bold px-2 py-1 rounded-full 
                        {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : 
                           ($order->status === 'preparing' ? 'bg-blue-100 text-blue-700' : 
                           ($order->status === 'ready' ? 'bg-emerald-100 text-emerald-700' : 
                           ($order->status === 'completed' ? 'bg-zinc-100 text-zinc-600' : 'bg-red-100 text-red-700'))) }}">
                        {{ $order->status_label }}
                    </span>
                    <div class="text-xs text-zinc-400 mt-1">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-sm text-zinc-400">Belum ada pesanan hari ini</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Topup Chart --}}
<div class="mt-4 bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-xl bg-violet-100 flex items-center justify-center">
                <iconify-icon icon="solar:wallet-bold-duotone" class="text-base text-violet-600"></iconify-icon>
            </div>
            <h3 class="font-bold text-zinc-900 text-sm">Top-Up Harian — 7 Hari Terakhir</h3>
        </div>
        <div class="flex items-center gap-4 text-xs text-zinc-400">
            <span>Total: <strong class="text-violet-700">Rp {{ number_format($totalTopup, 0, ',', '.') }}</strong></span>
            <span>{{ $totalTopupCount }}× transaksi</span>
        </div>
    </div>
    <div class="p-6">
        <canvas id="topupChart" height="70"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($revenueChart, 'date')) !!},
        datasets: [{
            label: 'Pendapatan',
            data: {!! json_encode(array_column($revenueChart, 'revenue')) !!},
            backgroundColor: 'rgba(24, 24, 27, 0.08)',
            borderColor: '#18181b',
            borderWidth: 2,
            borderRadius: 8,
            hoverBackgroundColor: 'rgba(24,24,27,0.15)',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.04)' }, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID'), font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});

// Topup Chart
const topupCtx = document.getElementById('topupChart').getContext('2d');
new Chart(topupCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($topupChart, 'date')) !!},
        datasets: [
            {
                label: 'Jumlah Top-Up',
                data: {!! json_encode(array_column($topupChart, 'amount')) !!},
                borderColor: '#7c3aed',
                backgroundColor: 'rgba(124,58,237,0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#7c3aed',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4,
                yAxisID: 'yAmount',
            },
            {
                label: 'Jumlah Transaksi',
                data: {!! json_encode(array_column($topupChart, 'count')) !!},
                borderColor: '#f59e0b',
                backgroundColor: 'transparent',
                borderWidth: 2,
                borderDash: [5, 4],
                pointBackgroundColor: '#f59e0b',
                pointRadius: 3,
                pointHoverRadius: 5,
                fill: false,
                tension: 0.4,
                yAxisID: 'yCount',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: true, position: 'top', labels: { font: { size: 11 }, boxWidth: 12 } },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        if (ctx.datasetIndex === 0) return ' Rp ' + ctx.raw.toLocaleString('id-ID');
                        return ' ' + ctx.raw + 'x transaksi';
                    }
                }
            }
        },
        scales: {
            yAmount: {
                beginAtZero: true,
                position: 'left',
                grid: { color: 'rgba(0,0,0,.04)' },
                ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID'), font: { size: 10 } }
            },
            yCount: {
                beginAtZero: true,
                position: 'right',
                grid: { display: false },
                ticks: { stepSize: 1, font: { size: 10 } }
            },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush
