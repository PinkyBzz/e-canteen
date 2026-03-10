@extends('layouts.user')
@section('title', 'Checkout')

@section('content')
<div class="fixed inset-0 pointer-events-none z-[-1] overflow-hidden bg-zinc-50">
    <div class="absolute -top-1/4 -right-1/4 w-3/4 h-3/4 bg-blue-300/30 rounded-full blur-[120px]"></div>
    <div class="absolute top-1/2 -left-1/4 w-3/4 h-3/4 bg-purple-300/30 rounded-full blur-[100px]"></div>
    <div class="absolute -bottom-1/4 left-1/2 w-1/2 h-1/2 bg-yellow-300/20 rounded-full blur-[90px]"></div>
</div>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-32">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-12 h-12 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shadow-lg shadow-zinc-900/20">
            <iconify-icon icon="solar:bill-check-linear" class="text-2xl"></iconify-icon>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-zinc-900">Checkout</h1>
            <p class="text-zinc-500 text-sm">Selesaikan pesanan kamu</p>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-red-50/80 backdrop-blur-md border border-red-100 text-red-600 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 shadow-sm">
        <iconify-icon icon="solar:danger-triangle-bold" class="text-xl"></iconify-icon>
        <p class="font-medium text-sm">{{ session('error') }}</p>
    </div>
    @endif
    
    @if($errors->any())
    <div class="bg-red-50/80 backdrop-blur-md border border-red-100 text-red-600 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 shadow-sm">
        <iconify-icon icon="solar:danger-triangle-bold" class="text-xl"></iconify-icon>
        <div class="font-medium text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Review Items -->
        <div class="space-y-6">
            <div class="bg-white/60 backdrop-blur-xl border border-white/80 rounded-3xl p-6 md:p-8 shadow-sm">
                <h3 class="font-semibold text-lg text-zinc-900 mb-6 flex items-center gap-2">
                    <iconify-icon icon="solar:box-minimalistic-linear" class="text-zinc-500"></iconify-icon>
                    Review Pesanan
                </h3>
                
                <div class="space-y-4">
                    @foreach($cart as $item)
                    <div class="flex items-center gap-4">
                        @if($item['photo'])
                        <img src="{{ asset('storage/' . $item['photo']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 rounded-xl object-cover shrink-0 border border-zinc-100">
                        @else
                        <div class="w-16 h-16 rounded-xl bg-zinc-100 flex items-center justify-center shrink-0">
                            <iconify-icon icon="solar:cup-hot-linear" class="text-2xl text-zinc-400"></iconify-icon>
                        </div>
                        @endif
                        <div class="flex-grow">
                            <h4 class="font-medium text-zinc-900 leading-tight mb-1">{{ $item['name'] }}</h4>
                            <div class="text-sm text-zinc-500 flex items-center gap-2">
                                <span>{{ $item['quantity'] }}x</span>
                                <span class="w-1 h-1 rounded-full bg-zinc-300"></span>
                                <span>Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="font-semibold text-zinc-900 shrink-0">
                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <a href="{{ route('user.cart.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">
                <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>
                Kembali ke Keranjang
            </a>
        </div>

        <!-- Checkout Form & Summary -->
        <div>
            <div class="bg-white/60 backdrop-blur-xl border border-white/80 rounded-3xl p-6 md:p-8 shadow-sm">
                <form method="POST" action="{{ route('user.orders.store') }}" id="checkoutForm">
                    @csrf
                    
                    <h3 class="font-semibold text-lg text-zinc-900 mb-6 flex items-center gap-2">
                        <iconify-icon icon="solar:notes-linear" class="text-zinc-500"></iconify-icon>
                        Detail Pengambilan
                    </h3>
                    
                    <div class="space-y-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-3">Waktu Pengambilan <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="break_time" value="istirahat_1" class="peer sr-only" required>
                                    <div class="rounded-2xl border-2 border-zinc-200 bg-white p-4 hover:bg-zinc-50 transition-all peer-checked:border-zinc-900 peer-checked:bg-zinc-50">
                                        <div class="flex items-center gap-2 mb-1">
                                            <iconify-icon icon="solar:clock-circle-bold-duotone" class="text-zinc-900 text-lg"></iconify-icon>
                                            <span class="font-semibold text-zinc-900">Istirahat 1</span>
                                        </div>
                                        <p class="text-xs text-zinc-500 ml-6">09:30 - 10:00</p>
                                    </div>
                                    <div class="absolute top-4 right-4 text-zinc-900 opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
                                    </div>
                                </label>
                                
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="break_time" value="istirahat_2" class="peer sr-only" required>
                                    <div class="rounded-2xl border-2 border-zinc-200 bg-white p-4 hover:bg-zinc-50 transition-all peer-checked:border-zinc-900 peer-checked:bg-zinc-50">
                                        <div class="flex items-center gap-2 mb-1">
                                            <iconify-icon icon="solar:clock-circle-bold-duotone" class="text-zinc-900 text-lg"></iconify-icon>
                                            <span class="font-semibold text-zinc-900">Istirahat 2</span>
                                        </div>
                                        <p class="text-xs text-zinc-500 ml-6">12:00 - 12:30</p>
                                    </div>
                                    <div class="absolute top-4 right-4 text-zinc-900 opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <iconify-icon icon="solar:check-circle-bold" class="text-xl"></iconify-icon>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-zinc-700 mb-2">Catatan Pesanan <span class="text-zinc-400 font-normal">(Opsional)</span></label>
                            <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-3 rounded-2xl bg-zinc-50/50 border border-zinc-200 focus:outline-none focus:ring-2 focus:ring-zinc-900/10 focus:border-zinc-300 transition-all text-sm text-zinc-900 placeholder:text-zinc-400 resize-none" placeholder="Misal: Jangan pedas ya, es dipisah..."></textarea>
                        </div>
                    </div>

                    <div class="border-t border-zinc-200/50 pt-6 mb-6 space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-zinc-500">Saldo Saat Ini</span>
                            <span class="font-medium text-zinc-900">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-zinc-500">Total Harga</span>
                            <span class="font-medium text-red-500">- Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="h-px bg-zinc-200/50 w-full my-4"></div>
                        
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-zinc-900">Sisa Saldo</span>
                            <span class="font-bold text-lg {{ (auth()->user()->balance - $cartTotal) < 0 ? 'text-red-500' : 'text-green-600' }}">
                                Rp {{ number_format(auth()->user()->balance - $cartTotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    @if(auth()->user()->balance < $cartTotal)
                    <div class="bg-red-50 text-red-600 p-4 rounded-2xl flex items-start gap-3 mb-6 text-sm border border-red-100">
                        <iconify-icon icon="solar:danger-triangle-bold" class="text-lg shrink-0 mt-0.5"></iconify-icon>
                        <div>
                            <p class="font-bold mb-1">Saldo Tidak Cukup</p>
                            <p>Saldo kamu kurang Rp {{ number_format($cartTotal - auth()->user()->balance, 0, ',', '.') }} untuk melakukan pemesanan.</p>
                        </div>
                    </div>
                    @endif

                    <button type="button" 
                            onclick="confirmOrder()" 
                            class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl text-white font-semibold transition-all duration-200 shadow-lg shadow-zinc-900/20 {{ auth()->user()->balance < $cartTotal ? 'bg-zinc-400 cursor-not-allowed pointer-events-none' : 'bg-zinc-900 hover:bg-zinc-800 hover:-translate-y-0.5' }}"
                            {{ auth()->user()->balance < $cartTotal ? 'disabled' : '' }}>
                        <iconify-icon icon="solar:wallet-money-linear" class="text-xl"></iconify-icon>
                        Bayar & Pesan Sekarang
                    </button>
                    <p class="text-center text-xs text-zinc-400 mt-4">Pesanan tidak dapat dibatalkan setelah dibayar</p>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 bg-zinc-900/40 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-sm">
        <div class="bg-white/80 backdrop-blur-2xl rounded-3xl p-6 md:p-8 shadow-2xl border border-white/50 transform scale-95 transition-transform duration-300" id="modalContent">
            <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4">
                <iconify-icon icon="solar:question-circle-bold-duotone" class="text-3xl text-amber-500"></iconify-icon>
            </div>
            <h3 class="text-xl font-bold text-center text-zinc-900 mb-2">Konfirmasi Pesanan</h3>
            <p class="text-center text-zinc-500 text-sm mb-8">Pastikan waktu pengambilan sudah benar. Saldo kamu akan dipotong sesuai total harga.</p>
            
            <div class="flex gap-3">
                <button onclick="closeModal()" class="flex-1 py-3 px-4 rounded-xl font-medium text-zinc-700 bg-zinc-100 hover:bg-zinc-200 transition-colors">Batal</button>
                <button onclick="submitForm()" class="flex-1 py-3 px-4 rounded-xl font-medium text-white bg-zinc-900 hover:bg-zinc-800 transition-colors shadow-lg shadow-zinc-900/20">Ya, Pesan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('confirmModal');
    const modalContent = document.getElementById('modalContent');
    const form = document.getElementById('checkoutForm');

    function confirmOrder() {
        // Validate form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        modal.classList.remove('hidden');
        // trigger reflow
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }

    function closeModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function submitForm() {
        form.submit();
    }
</script>
@endpush
