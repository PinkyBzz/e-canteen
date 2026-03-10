@extends('layouts.user')
@section('title', 'Keranjang Belanja')

@section('content')
<!-- Ambient Effects layer -->
<div class="fixed inset-0 pointer-events-none z-[-1] overflow-hidden bg-zinc-50">
    <div class="absolute -top-1/4 -right-1/4 w-3/4 h-3/4 bg-blue-300/30 rounded-full blur-[120px]"></div>
    <div class="absolute top-1/2 -left-1/4 w-3/4 h-3/4 bg-purple-300/30 rounded-full blur-[100px]"></div>
    <div class="absolute -bottom-1/4 left-1/2 w-1/2 h-1/2 bg-yellow-300/20 rounded-full blur-[90px]"></div>
</div>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-32">
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shadow-lg shadow-zinc-900/20">
                <iconify-icon icon="solar:cart-large-4-linear" class="text-2xl"></iconify-icon>
            </div>
            <h1 class="text-2xl font-bold text-zinc-900">Keranjang Belanja</h1>
        </div>

        @if(!empty($cart))
        <form method="POST" action="{{ route('user.cart.clear') }}" onsubmit="return confirm('Kosongkan semua di keranjang?')">
            @csrf @method('DELETE')
            <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors font-medium text-sm border border-transparent shadow-sm">
                <iconify-icon icon="solar:trash-bin-trash-linear" class="text-lg"></iconify-icon>
                Kosongkan
            </button>
        </form>
        @endif
    </div>

    @if(empty($cart))
    <div class="bg-white/60 backdrop-blur-xl border border-white/80 rounded-3xl p-12 text-center shadow-sm flex flex-col items-center">
        <div class="w-24 h-24 rounded-full bg-zinc-100 flex items-center justify-center mb-6">
            <iconify-icon icon="solar:cart-cross-linear" class="text-5xl text-zinc-300"></iconify-icon>
        </div>
        <h3 class="text-xl font-semibold text-zinc-900 mb-2">Keranjangmu masih kosong</h3>
        <p class="text-zinc-500 mb-8 max-w-sm">Sepertinya kamu belum menambahkan makanan atau minuman ke keranjang.</p>
        <a href="{{ route('user.home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-zinc-900 text-white font-medium hover:bg-zinc-800 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 text-decoration-none shadow-[0_0_20px_rgba(0,0,0,0.1)]">
            <iconify-icon icon="solar:shop-linear" class="text-lg"></iconify-icon>
            Lihat Menu
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items List -->
        <div class="lg:col-span-2 space-y-4">
            @foreach($cart as $key => $item)
            <div class="bg-white/60 backdrop-blur-xl border border-white/80 rounded-3xl p-4 sm:p-6 shadow-sm hover:shadow-lg hover:shadow-zinc-200/50 transition-all duration-300 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 group">
                
                @if($item['photo'])
                <img src="{{ asset('storage/' . $item['photo']) }}" alt="{{ $item['name'] }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover shrink-0">
                @else
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-zinc-100 flex items-center justify-center shrink-0">
                    <iconify-icon icon="solar:cup-hot-linear" class="text-3xl text-zinc-400"></iconify-icon>
                </div>
                @endif
                
                <div class="flex-grow flex flex-col sm:flex-row justify-between w-full h-full gap-4">
                    <div class="flex flex-col justify-center">
                        <h3 class="text-lg font-semibold text-zinc-900 leading-tight mb-1">{{ $item['name'] }}</h3>
                        <p class="text-zinc-500 font-medium text-sm">Rp {{ number_format($item['price'], 0, ',', '.') }} <span class="text-zinc-400 font-normal">/ porsi</span></p>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-6 sm:w-auto">
                        <!-- Up/Down Controls -->
                        <form method="POST" action="{{ route('user.cart.update', $item['menu_id']) }}" class="flex items-center bg-zinc-100/80 rounded-xl p-1 shrink-0">
                            @csrf @method('PATCH')
                            <button type="submit" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-zinc-500 hover:bg-white hover:text-zinc-900 hover:shadow-sm transition-all duration-200">
                                <iconify-icon icon="solar:minus-square-linear"></iconify-icon>
                            </button>
                            <span class="w-10 text-center font-semibold text-zinc-900">{{ $item['quantity'] }}</span>
                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-zinc-500 hover:bg-white hover:text-zinc-900 hover:shadow-sm transition-all duration-200">
                                <iconify-icon icon="solar:add-square-linear"></iconify-icon>
                            </button>
                        </form>
                        
                        <div class="flex items-center gap-4 shrink-0">
                            <span class="font-bold text-lg text-zinc-900 min-w-[90px] text-right">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            <!-- Delete button -->
                            <form method="POST" action="{{ route('user.cart.remove', $item['menu_id']) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-2xl flex items-center justify-center text-red-400 bg-red-50 hover:bg-red-500 hover:text-white transition-colors duration-200 group/btn tooltip" title="Hapus Item">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-linear" class="text-xl"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Summary Widget -->
        <div class="lg:col-span-1">
            <div class="bg-white/60 backdrop-blur-xl border border-white/80 rounded-3xl p-6 shadow-sm sticky top-24">
                <h3 class="text-lg font-bold text-zinc-900 mb-6">Ringkasan Pesanan</h3>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center text-zinc-500">
                        <span>Subtotal ({{ count($cart) }} item)</span>
                        <span class="font-semibold text-zinc-900">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center text-zinc-500">
                        <span>Saldo Tersedia</span>
                        <span class="font-semibold {{ auth()->user()->balance >= $cartTotal ? 'text-green-600' : 'text-red-500' }}">
                            Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                @if(auth()->user()->balance < $cartTotal)
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl flex items-start gap-3 mb-6 text-sm border border-red-100">
                    <iconify-icon icon="solar:danger-triangle-bold" class="text-lg shrink-0 mt-0.5"></iconify-icon>
                    <p>Saldo kamu tidak mencukupi untuk pesanan ini. Silakan top up ke kasir / admin.</p>
                </div>
                @endif

                <div class="border-t border-zinc-200/50 pt-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-zinc-900">Total Bayar</span>
                        <span class="font-bold text-xl text-zinc-900">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <a href="{{ route('user.orders.checkout') }}" class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl text-white font-semibold transition-all duration-200 shadow-lg shadow-zinc-900/20 mb-3 {{ auth()->user()->balance < $cartTotal ? 'bg-zinc-400 cursor-not-allowed pointer-events-none' : 'bg-zinc-900 hover:bg-zinc-800 hover:-translate-y-0.5' }}">
                    <iconify-icon icon="solar:bill-check-linear" class="text-xl"></iconify-icon>
                    Lanjut Checkout
                </a>
                
                <a href="{{ route('user.home') }}" class="w-full flex items-center justify-center gap-2 py-3 px-6 rounded-2xl bg-zinc-100 text-zinc-700 font-medium hover:bg-zinc-200 transition-colors text-decoration-none border border-transparent">
                    Tambah Menu Lain
                </a>
            </div>
        </div>
    </div>
    @endif
</main>
@endsection
