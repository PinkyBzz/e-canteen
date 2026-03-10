@extends('layouts.user')

@section('content')
<!-- Ambient Effects layer -->
<div class="fixed inset-0 pointer-events-none z-[-1] overflow-hidden bg-zinc-50">
    <div class="absolute -top-1/4 -right-1/4 w-3/4 h-3/4 bg-blue-300/30 rounded-full blur-[120px]"></div>
    <div class="absolute top-1/2 -left-1/4 w-3/4 h-3/4 bg-purple-300/30 rounded-full blur-[100px]"></div>
    <div class="absolute -bottom-1/4 left-1/2 w-1/2 h-1/2 bg-yellow-300/20 rounded-full blur-[90px]"></div>
</div>

<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-32">
    <!-- Hero Section -->
    <section class="relative rounded-[2.5rem] overflow-hidden mb-12 shadow-sm border border-white/80 bg-white/40 backdrop-blur-xl group">
        <!-- Vibrant background abstract shapes -->
        <div class="absolute -right-20 -top-20 w-[400px] h-[400px] bg-gradient-to-br from-blue-300/40 to-purple-400/40 rounded-full blur-[80px] group-hover:scale-110 transition-transform duration-1000 z-0 ease-in-out"></div>
        <div class="absolute -left-20 -bottom-20 w-[300px] h-[300px] bg-gradient-to-tr from-emerald-300/40 to-yellow-300/40 rounded-full blur-[60px] z-0"></div>

        <div class="relative z-10 p-8 sm:p-12 md:p-16 flex flex-col md:flex-row items-center justify-between gap-12 w-full">
            <div class="w-full md:w-3/5">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/60 border border-white/80 text-zinc-700 text-xs font-bold backdrop-blur-md mb-6 shadow-[0_4px_12px_rgba(0,0,0,0.05)]">
                    <iconify-icon icon="solar:confetti-bold-duotone" class="text-amber-500 text-lg"></iconify-icon>
                    <span class="tracking-wide uppercase">Selamat Datang di Kantin 40</span>
                </div>
                
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-zinc-900 leading-[1.1] mb-6 text-balance">
                    Halo, {{ auth()->user()->name }}!<br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">Makan enak tanpa antre.</span>
                </h2>
                
                <p class="text-base md:text-lg text-zinc-500 max-w-lg mb-10 leading-relaxed font-medium">
                    Pesan makanan dan minuman favoritmu sekarang, ambil saat bel istirahat berbunyi. Waktumu terlalu berharga untuk dihabiskan mengantre.
                </p>
                
                <div class="flex flex-wrap items-center gap-4">
                    <button onclick="document.getElementById('filter-form').scrollIntoView({behavior: 'smooth', block: 'start'})" style="border-radius:9999px" class="inline-flex items-center justify-center gap-2 py-4 px-8 bg-zinc-900 text-white font-bold text-sm hover:bg-zinc-800 hover:-translate-y-1 hover:shadow-xl hover:shadow-zinc-900/20 active:scale-95 transition-all duration-300 leading-none overflow-hidden">
                        <iconify-icon icon="solar:shop-linear" style="font-size:1.2rem;display:block;flex-shrink:0"></iconify-icon>
                        Pesan Sekarang
                    </button>
                    <a href="{{ route('user.orders.history') }}" style="border-radius:9999px;text-decoration:none" class="inline-flex items-center justify-center py-4 px-8 bg-white/80 backdrop-blur-xl text-zinc-700 font-bold text-sm hover:bg-white hover:-translate-y-1 hover:shadow-lg border border-white/80 active:scale-95 transition-all duration-300 leading-none overflow-hidden">
                        Riwayat Pesanan
                    </a>
                </div>
            </div>
            
            <!-- Right Illustration -->
            <div class="w-full md:w-2/5 flex justify-center md:justify-end hidden md:flex">
                <div class="relative w-64 h-64 lg:w-80 lg:h-80 select-none pointer-events-none">
                    <!-- Glowing backing -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-amber-300 to-orange-500 rounded-full blur-[60px] opacity-30 animate-[pulse_4s_infinite_alternate]"></div>
                    
                    <!-- Main image placeholder -->
                    <div class="relative w-full h-full bg-white/60 backdrop-blur-2xl border-4 border-white shadow-2xl rounded-full flex items-center justify-center transform -rotate-6 group-hover:rotate-0 group-hover:scale-105 transition-all duration-700 ease-out z-10">
                        <iconify-icon icon="noto:hamburger" class="text-[120px] lg:text-[150px] drop-shadow-xl"></iconify-icon>
                    </div>
                    
                    <!-- Little floating elements -->
                    <div class="absolute top-8 -left-8 bg-white/90 backdrop-blur-xl p-4 rounded-3xl shadow-2xl border border-white z-20 animate-[bounce_3s_infinite]">
                        <iconify-icon icon="noto:french-fries" class="text-4xl"></iconify-icon>
                    </div>
                    <div class="absolute bottom-12 -right-6 bg-white/90 backdrop-blur-xl p-4 rounded-3xl shadow-2xl border border-white z-20 animate-[bounce_4s_infinite_0.5s]">
                        <iconify-icon icon="noto:bubble-tea" class="text-4xl"></iconify-icon>
                    </div>
                    <div class="absolute -top-4 right-10 bg-white/90 backdrop-blur-xl p-3 rounded-2xl shadow-xl border border-white z-0 animate-[bounce_3.5s_infinite_1s]">
                        <iconify-icon icon="noto:sparkles" class="text-2xl"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Warung Selection -->
    @if($warungs->isNotEmpty())
    <section class="mb-8">
        <h3 class="text-sm font-semibold text-zinc-500 uppercase tracking-widest mb-3 px-1">Pilih Warung</h3>
        <div class="flex flex-wrap gap-3">
            {{-- All --}}
            <a href="{{ route('user.home', array_filter(['search' => request('search'), 'category' => request('category')])) }}"
                class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl border text-sm font-semibold transition-all duration-200 text-decoration-none
                {{ !request('warung') ? 'bg-zinc-900 text-white border-zinc-900 shadow-lg shadow-zinc-900/20' : 'bg-white/70 backdrop-blur-xl border-white/90 text-zinc-600 hover:bg-white hover:text-zinc-900' }}">
                <iconify-icon icon="solar:shop-2-linear" class="text-base"></iconify-icon>
                Semua Warung
            </a>
            @foreach($warungs as $w)
            <a href="{{ route('user.home', array_filter(['search' => request('search'), 'category' => request('category'), 'warung' => $w->id])) }}"
                class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl border text-sm font-semibold transition-all duration-200 text-decoration-none
                {{ request('warung') == $w->id ? 'bg-amber-500 text-white border-amber-500 shadow-lg shadow-amber-500/25' : 'bg-white/70 backdrop-blur-xl border-white/90 text-zinc-600 hover:bg-white hover:text-zinc-900' }}">
                @if($w->logo)
                    <img src="{{ asset('storage/'.$w->logo) }}" alt="{{ $w->name }}" class="w-5 h-5 rounded-full object-cover">
                @else
                    <iconify-icon icon="solar:shop-linear" class="{{ request('warung') == $w->id ? 'text-white' : 'text-amber-400' }} text-base"></iconify-icon>
                @endif
                {{ $w->name }}
            </a>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Controls / Filters -->
    <section class="mb-8">
        {{-- Search --}}
        <form method="GET" action="{{ route('user.home') }}" id="filter-form" class="mb-4">
            <input type="hidden" name="category" value="{{ request('category') }}">
            <input type="hidden" name="warung" value="{{ request('warung') }}">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-zinc-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari makanan atau minuman..."
                    class="w-full h-12 pl-12 pr-4 rounded-2xl bg-white/70 backdrop-blur-xl border border-white/90 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-200 focus:bg-white transition-all duration-200"
                    onchange="this.form.submit()"
                />
            </div>
        </form>

        {{-- Category Pills --}}
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('user.home', array_filter(['search' => request('search'), 'warung' => request('warung')])) }}"
                class="px-4 py-2 rounded-full text-sm font-semibold border transition-all duration-200 text-decoration-none {{ !request('category') ? 'bg-zinc-900 text-white border-zinc-900' : 'bg-white/70 backdrop-blur-xl border-white/90 text-zinc-600 hover:bg-white hover:text-zinc-900' }}">
                Semua
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('user.home', array_filter(['search' => request('search'), 'warung' => request('warung'), 'category' => $cat])) }}"
                class="px-4 py-2 rounded-full text-sm font-semibold border transition-all duration-200 text-decoration-none {{ request('category') == $cat ? 'bg-zinc-900 text-white border-zinc-900' : 'bg-white/70 backdrop-blur-xl border-white/90 text-zinc-600 hover:bg-white hover:text-zinc-900' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
    </section>

    {{-- Empty state --}}
    @if($menus->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-zinc-400">
        <iconify-icon icon="solar:ghost-smile-linear" class="text-6xl mb-4 text-zinc-300"></iconify-icon>
        <p class="text-lg font-medium">Tidak ada menu yang ditemukan</p>
        <p class="text-sm mt-1">Coba pilih warung atau kategori lain</p>
    </div>
    @else
    {{-- Menu Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-12">
        @foreach($menus as $menu)
        <article class="bg-white/80 backdrop-blur-xl rounded-3xl border border-white/90 shadow-sm hover:shadow-xl hover:border-zinc-200 transition-all duration-300 overflow-hidden flex flex-col">
            {{-- Image --}}
            <div class="relative aspect-video bg-zinc-100 overflow-hidden">
                @if($menu->photo)
                    <img src="{{ asset('storage/'.$menu->photo) }}" alt="{{ $menu->name }}"
                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <iconify-icon icon="solar:dish-linear" class="text-5xl text-zinc-300"></iconify-icon>
                    </div>
                @endif
                {{-- Category badge --}}
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 bg-white/95 backdrop-blur-sm rounded-full text-xs font-semibold text-zinc-700 shadow-sm border border-zinc-100">
                        {{ $menu->category }}
                    </span>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-5 flex flex-col flex-grow">
                {{-- Warung badge --}}
                @if($menu->warung)
                <div class="flex items-center gap-1.5 mb-2">
                    <iconify-icon icon="solar:shop-linear" class="text-amber-500 text-xs"></iconify-icon>
                    <span class="text-xs font-semibold text-amber-600">{{ $menu->warung->name }}</span>
                </div>
                @endif

                <h3 class="font-bold text-base text-zinc-900 leading-tight mb-1">{{ $menu->name }}</h3>
                <p class="text-sm text-zinc-500 line-clamp-2 mb-4 leading-relaxed flex-grow">
                    {{ $menu->description ?: 'Hidangan lezat untuk santapan Anda hari ini.' }}
                </p>

                <div class="flex items-center justify-between mt-auto">
                    <div>
                        <span class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider block mb-0.5">Harga</span>
                        <span class="font-bold text-lg text-zinc-900">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                    </div>
                    <button
                        onclick="addToCart({{ $menu->id }}, this)"
                        class="w-11 h-11 rounded-full bg-zinc-900 hover:bg-zinc-700 flex items-center justify-center transition-all hover:-translate-y-1 active:translate-y-0 shadow-md hover:shadow-xl"
                        style="color:white;border:none;cursor:pointer"
                        title="Tambah ke keranjang">
                        <iconify-icon icon="solar:cart-plus-linear" class="text-lg" style="color:white"></iconify-icon>
                    </button>
                </div>
            </div>
        </article>
        @endforeach
    </div>
    @endif
</main>
@endsection

@push('scripts')
<script>
const CSRF = "{{ csrf_token() }}";
function addToCart(menuId, btn) {
    btn.disabled = true;
    btn.innerHTML = '<iconify-icon icon="solar:spinner-linear" class="text-lg animate-spin" style="color:white"></iconify-icon>';
    fetch('/cart/add/' + menuId, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: new URLSearchParams({ quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = '<iconify-icon icon="solar:check-circle-linear" class="text-lg" style="color:white"></iconify-icon>';
        if (window.showFloatingCart) window.showFloatingCart();
        if (window.updateCartBadge) window.updateCartBadge(1);
        setTimeout(() => {
            btn.innerHTML = '<iconify-icon icon="solar:cart-plus-linear" class="text-lg" style="color:white"></iconify-icon>';
            btn.disabled = false;
        }, 1500);
    })
    .catch(() => {
        btn.innerHTML = '<iconify-icon icon="solar:cart-plus-linear" class="text-lg" style="color:white"></iconify-icon>';
        btn.disabled = false;
    });
}
</script>
@endpush
