<x-guest-layout>
    <h2 class="text-xl font-bold text-zinc-900 text-center mb-6">Buat Akun Baru</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-semibold text-zinc-700 mb-1.5">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                required autofocus autocomplete="name"
                placeholder="Nama kamu..."
                class="w-full h-12 px-4 rounded-2xl bg-white/80 border {{ $errors->has('name') ? 'border-red-400' : 'border-zinc-200' }} text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-200 focus:border-transparent transition-all">
            @error('name')
                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-zinc-700 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                required autocomplete="username"
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
                required autocomplete="new-password"
                placeholder="Min. 8 karakter"
                class="w-full h-12 px-4 rounded-2xl bg-white/80 border {{ $errors->has('password') ? 'border-red-400' : 'border-zinc-200' }} text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-200 focus:border-transparent transition-all">
            @error('password')
                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-zinc-700 mb-1.5">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                required autocomplete="new-password"
                placeholder="Ulangi password"
                class="w-full h-12 px-4 rounded-2xl bg-white/80 border {{ $errors->has('password_confirmation') ? 'border-red-400' : 'border-zinc-200' }} text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-200 focus:border-transparent transition-all">
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full h-12 rounded-2xl bg-zinc-900 text-white font-bold text-sm hover:bg-zinc-800 active:scale-[.98] transition-all shadow-lg shadow-zinc-900/10 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8zM19 8v6M22 11h-6"/>
            </svg>
            Daftar Sekarang
        </button>

        <p class="text-center text-sm text-zinc-500 pt-1">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-bold text-zinc-900 hover:underline" style="text-decoration:none">Masuk</a>
        </p>
    </form>
</x-guest-layout>
