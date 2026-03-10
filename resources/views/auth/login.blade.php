<x-guest-layout>
    @if (session('status'))
        <div class="mb-5 p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
            {{ session('status') }}
        </div>
    @endif

    <h2 class="text-xl font-bold text-zinc-900 text-center mb-6">Masuk ke Akun</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-zinc-700 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                required autofocus autocomplete="username"
                placeholder="email@example.com"
                class="w-full h-12 px-4 rounded-2xl bg-white/80 border {{ $errors->has('email') ? 'border-red-400' : 'border-zinc-200' }} text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-200 focus:border-transparent transition-all">
            @error('email')
                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-semibold text-zinc-700 mb-1.5">Password</label>
            <input id="password" type="password" name="password"
                required autocomplete="current-password"
                placeholder="••••••••"
                class="w-full h-12 px-4 rounded-2xl bg-white/80 border {{ $errors->has('password') ? 'border-red-400' : 'border-zinc-200' }} text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-200 focus:border-transparent transition-all">
            @error('password')
                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember + Forgot --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" id="remember_me" class="w-4 h-4 rounded border-zinc-300 accent-zinc-900">
                <span class="text-sm text-zinc-500 font-medium">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-zinc-500 hover:text-zinc-900 transition-colors" style="text-decoration:none">
                    Lupa password?
                </a>
            @endif
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full h-12 rounded-2xl bg-zinc-900 text-white font-bold text-sm hover:bg-zinc-800 active:scale-[.98] transition-all shadow-lg shadow-zinc-900/10 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
            </svg>
            Masuk
        </button>

        @if (Route::has('register'))
        <p class="text-center text-sm text-zinc-500 pt-1">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-bold text-zinc-900 hover:underline" style="text-decoration:none">Daftar sekarang</a>
        </p>
        @endif


    </form>
</x-guest-layout>
