@extends('layouts.user')
@section('title', 'Menu Kantin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 pt-8 pb-32">

    {{-- Hero --}}
    <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-brand/10 mb-8 animate-fsu">
        <div class="absolute inset-0 bg-gradient-to-br from-brand via-blue-600 to-indigo-700"></div>
        {{-- decorative circles --}}
        <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-8 -left-8 w-36 h-36 rounded-full bg-white/5"></div>
        <div class="relative p-8 sm:p-12 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="max-w-xl text-center md:text-left">
                <h2 class="text-3xl sm:text-4xl font-bold text-white leading-tight">
                    Selamat datang, {{ auth()->user()->name }}! 👋
                </h2>
                <p class="mt-3 text-blue-100 text-base">
                    Pesan sekarang, ambil saat istirahat — tanpa antre panjang.
                </p>
                <div class="mt-6 flex flex-wrap gap-3 justify-center md:justify-start">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white/20 backdrop-blur-sm text-white text-sm">
                        <i class="bi bi-wallet2"></i>
                        <span class="font-semibold">Saldo: Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('user.cart.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-brand text-sm font-bold shadow-xl hover:bg-blue-50 transition">
                        <i class="bi bi-bag"></i> Lihat Keranjang
                    </a>
                </div>
            </div>
            <div class="hidden md:flex w-40 h-40 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 items-center justify-center text-7xl opacity-80 shrink-0">
                🍱
            </div>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="glass rounded-2xl p-4 mb-8 animate-fsu" style="animation-delay:.1s">
        <form method="GET" action="{{ route('user.home') }}" class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
            {{-- Search --}}
            <div class="relative flex-1">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input
                    type="text" name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari menu lezat..."
                    class="w-full h-11 pl-10 pr-4 rounded-xl bg-white/60 border border-white/40 text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 transition"
                >
            </div>
            {{-- Category select --}}
            <select name="category"
                    class="h-11 px-3 rounded-xl bg-white/60 border border-white/40 text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 transition md:w-44">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            {{-- Buttons --}}
            <button type="submit"
                    class="h-11 px-6 rounded-xl bg-brand text-white text-sm font-semibold hover:bg-blue-700 transition shadow-lg shadow-brand/20">
                <i class="bi bi-funnel me-1"></i>Filter
            </button>
            <a href="{{ route('user.home') }}"
               class="h-11 px-5 rounded-xl bg-white/60 border border-white/40 text-slate-600 text-sm font-medium flex items-center justify-center hover:bg-white/80 transition">
                Reset
            </a>
        </form>

        {{-- Category pills --}}
        <div class="flex gap-2 mt-3 flex-wrap">
            <a href="{{ route('user.home') }}"
               class="px-4 py-1.5 rounded-full text-xs font-semibold transition
                      {{ !request('category') ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'bg-white/60 border border-white/40 text-slate-600 hover:bg-white/80' }}">
                Semua
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('user.home', ['category' => $cat, 'search' => request('search')]) }}"
               class="px-4 py-1.5 rounded-full text-xs font-semibold transition
                      {{ request('category') == $cat ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'bg-white/60 border border-white/40 text-slate-600 hover:bg-white/80' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-slate-900">Menu Unggulan</h3>
        <span class="text-sm text-slate-400 font-medium">{{ $menus->count() }} menu ditemukan</span>
    </div>

    {{-- Empty state --}}
    @if($menus->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-slate-400 animate-fsu">
        <div class="text-6xl mb-4">🔍</div>
        <p class="text-lg font-medium">Tidak ada menu yang ditemukan</p>
        <a href="{{ route('user.home') }}" class="mt-4 px-5 py-2 rounded-xl bg-brand/10 text-brand text-sm font-semibold">Reset Filter</a>
    </div>
    @else
    {{-- React menu grid mount point --}}
    <div id="menu-react-root"></div>
    @endif

</div>
@endsection

@push('scripts')
<script>
window.ECANTEEN = {
    menus: @json($menus->values()),
    csrfToken: "{{ csrf_token() }}",
    cartAddUrl: "{{ url('cart/add/__ID__') }}",
    cartUrl: "{{ route('user.cart.index') }}",
};
</script>
@vite(['resources/js/menu.jsx'])
@endpush
