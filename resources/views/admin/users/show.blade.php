@extends('layouts.admin')
@section('title', 'Detail User')
@section('page-title', 'Detail Pengguna')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- LEFT: Profile + Top-up --}}
    <div class="space-y-5">

        {{-- Profile card --}}
        <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-6 text-center">
            <div class="w-16 h-16 rounded-full bg-zinc-100 border border-zinc-200 flex items-center justify-center text-zinc-700 font-bold text-xl mx-auto mb-3">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <h3 class="font-semibold text-zinc-900 text-base mb-0.5">{{ $user->name }}</h3>
            <p class="text-sm text-zinc-500 mb-4">{{ $user->email }}</p>
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-3">
                <p class="text-xs text-zinc-500 mb-0.5">Saldo Virtual</p>
                <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($user->balance, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Top-up form --}}
        <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
            <div class="flex items-center gap-2 px-5 py-4 border-b border-zinc-100">
                <iconify-icon icon="solar:add-circle-linear" class="text-emerald-600 text-lg"></iconify-icon>
                <span class="font-semibold text-zinc-900 text-sm">Top Up Saldo</span>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('admin.users.topup', $user) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1.5">Jumlah Top Up</label>
                        <div class="flex rounded-xl border border-zinc-200 overflow-hidden bg-white/60 focus-within:ring-2 focus-within:ring-zinc-900/20 focus-within:border-zinc-400 transition">
                            <span class="px-3 py-2.5 bg-zinc-50 text-zinc-500 text-sm border-r border-zinc-200 font-medium">Rp</span>
                            <input type="number" name="amount" id="amountInput" placeholder="10000" min="1000" max="1000000" step="1000" required
                                class="flex-1 px-3 py-2.5 text-sm bg-transparent focus:outline-none">
                        </div>
                        {{-- Quick amounts --}}
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @foreach([10000, 20000, 50000, 100000] as $amt)
                            <button type="button"
                                class="quick-amount text-xs border border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 rounded-lg transition font-medium"
                                data-amount="{{ $amt }}">+{{ $amt/1000 }}rb</button>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1.5">Keterangan</label>
                        <input type="text" name="description" placeholder="Opsional..." maxlength="255"
                            class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900/20 focus:border-zinc-400 transition">
                    </div>
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2.5 rounded-xl transition">
                        <iconify-icon icon="solar:add-circle-linear"></iconify-icon>
                        Top Up Saldo
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT: Orders + Balance history --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Recent Orders --}}
        <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
            <div class="flex items-center gap-2 px-5 py-4 border-b border-zinc-100">
                <iconify-icon icon="solar:bag-3-linear" class="text-amber-500 text-lg"></iconify-icon>
                <span class="font-semibold text-zinc-900 text-sm">Riwayat Pesanan Terakhir</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-zinc-50/80 text-zinc-500 text-xs uppercase tracking-wide">
                            <th class="px-5 py-3 text-left font-medium">Kode</th>
                            <th class="px-4 py-3 text-left font-medium">Waktu Ambil</th>
                            <th class="px-4 py-3 text-right font-medium">Total</th>
                            <th class="px-4 py-3 text-center font-medium">Status</th>
                            <th class="px-5 py-3 text-right font-medium">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse($orders as $order)
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
                        <tr class="hover:bg-zinc-50/50 transition">
                            <td class="px-5 py-2.5 font-mono text-xs text-zinc-700">{{ $order->order_code }}</td>
                            <td class="px-4 py-2.5 text-zinc-600">{{ $order->break_time_label }}</td>
                            <td class="px-4 py-2.5 text-right text-zinc-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $sc }}">{{ $order->status_label }}</span>
                            </td>
                            <td class="px-5 py-2.5 text-right text-zinc-400 text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-5 py-6 text-center text-zinc-400 text-sm">Belum ada pesanan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Balance History --}}
        <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
            <div class="flex items-center gap-2 px-5 py-4 border-b border-zinc-100">
                <iconify-icon icon="solar:wallet-linear" class="text-blue-500 text-lg"></iconify-icon>
                <span class="font-semibold text-zinc-900 text-sm">Riwayat Saldo Terakhir</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-zinc-50/80 text-zinc-500 text-xs uppercase tracking-wide">
                            <th class="px-5 py-3 text-left font-medium">Keterangan</th>
                            <th class="px-4 py-3 text-center font-medium">Tipe</th>
                            <th class="px-4 py-3 text-right font-medium">Jumlah</th>
                            <th class="px-5 py-3 text-right font-medium">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse($balanceHistories as $bh)
                        <tr class="hover:bg-zinc-50/50 transition">
                            <td class="px-5 py-2.5 text-zinc-700">{{ $bh->description }}</td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $bh->type === 'credit' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                    {{ $bh->type === 'credit' ? 'Top Up' : 'Bayar' }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-right font-medium {{ $bh->type === 'credit' ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $bh->type === 'credit' ? '+' : '-' }}Rp {{ number_format($bh->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-2.5 text-right text-zinc-600">Rp {{ number_format($bh->balance_after, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-6 text-center text-zinc-400 text-sm">Belum ada riwayat saldo</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-5">
    <a href="{{ route('admin.users.index') }}"
        class="inline-flex items-center gap-2 text-sm text-zinc-600 hover:text-zinc-900 transition font-medium">
        <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>
        Kembali ke Daftar Pengguna
    </a>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.quick-amount').forEach(btn => {
    btn.addEventListener('click', function () {
        document.getElementById('amountInput').value = this.dataset.amount;
    });
});
</script>
@endpush
