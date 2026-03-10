<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <iconify-icon icon="solar:widget-5-linear" class="text-2xl text-zinc-700"></iconify-icon>
            <h2 class="font-medium text-xl text-zinc-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <!-- Ambient Background Inside Layout -->
    <div class="fixed inset-0 pointer-events-none z-[-1] overflow-hidden bg-zinc-50">
        <div class="absolute -top-1/4 -right-1/4 w-3/4 h-3/4 bg-blue-300/30 rounded-full blur-[120px]"></div>
        <div class="absolute top-1/2 -left-1/4 w-3/4 h-3/4 bg-purple-300/30 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-1/4 left-1/2 w-1/2 h-1/2 bg-yellow-300/20 rounded-full blur-[90px]"></div>
    </div>

    <div class="py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/60 backdrop-blur-xl border border-white/80 overflow-hidden shadow-sm sm:rounded-3xl hover:shadow-xl hover:shadow-zinc-200/50 transition-all duration-300">
                <div class="p-8 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 shrink-0">
                        <iconify-icon icon="solar:user-rounded-bold-duotone" class="text-3xl text-white"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-zinc-900 leading-tight mb-1">
                            Halo, {{ auth()->user()->name }}!
                        </h3>
                        <p class="text-zinc-500 text-sm">
                            {{ __("You're logged in!") }} Selamat datang di Kantin 40 Dashboard.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Tambahan Placeholder Cards untuk Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- Card 1 -->
                <div class="bg-white/60 backdrop-blur-xl border border-white/80 p-6 rounded-3xl shadow-sm hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center mb-4 group-hover:bg-blue-500 transition-colors">
                        <iconify-icon icon="solar:bag-3-linear" class="text-xl text-blue-500 group-hover:text-white transition-colors"></iconify-icon>
                    </div>
                    <h4 class="text-zinc-900 font-medium mb-1">Pesanan Aktif</h4>
                    <p class="text-2xl font-bold text-zinc-800">0</p>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-white/60 backdrop-blur-xl border border-white/80 p-6 rounded-3xl shadow-sm hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 flex items-center justify-center mb-4 group-hover:bg-amber-500 transition-colors">
                        <iconify-icon icon="solar:history-linear" class="text-xl text-amber-500 group-hover:text-white transition-colors"></iconify-icon>
                    </div>
                    <h4 class="text-zinc-900 font-medium mb-1">Riwayat Pesanan</h4>
                    <p class="text-2xl font-bold text-zinc-800">0</p>
                </div>
                
                <!-- Card 3 -->
                <div class="bg-white/60 backdrop-blur-xl border border-white/80 p-6 rounded-3xl shadow-sm hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4 group-hover:bg-emerald-500 transition-colors">
                        <iconify-icon icon="solar:wallet-money-linear" class="text-xl text-emerald-500 group-hover:text-white transition-colors"></iconify-icon>
                    </div>
                    <h4 class="text-zinc-900 font-medium mb-1">Total Pengeluaran</h4>
                    <p class="text-2xl font-bold text-zinc-800">Rp 0</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
