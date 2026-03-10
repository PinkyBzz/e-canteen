@extends('layouts.admin')
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-zinc-900">Daftar Pengguna</h2>
        <p class="text-sm text-zinc-400 font-medium mt-0.5">Kelola akun siswa/guru dan saldo virtual</p>
    </div>
</div>

{{-- Search --}}
<div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm mb-5">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}"
            class="h-10 px-4 rounded-2xl border border-zinc-200 bg-white/80 text-sm text-zinc-900 placeholder-zinc-400 flex-grow focus:outline-none focus:ring-2 focus:ring-zinc-200 transition-all">
        <button type="submit" class="h-10 px-5 rounded-2xl bg-zinc-900 text-white text-sm font-bold hover:bg-zinc-700 transition-colors">Cari</button>
        @if(request('search'))
        <a href="{{ route('admin.users.index') }}" class="h-10 px-5 rounded-2xl bg-zinc-100 text-zinc-600 text-sm font-semibold hover:bg-zinc-200 transition-colors flex items-center" style="text-decoration:none">Reset</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-zinc-100">
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">#</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Pengguna</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Saldo</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Total Pesanan</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Bergabung</th>
                <th class="text-center px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-50">
            @forelse($users as $user)
            <tr class="hover:bg-zinc-50/60 transition-colors">
                <td class="px-6 py-4 text-zinc-400 font-medium">{{ $loop->iteration }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-zinc-100 border border-zinc-200 flex items-center justify-center text-sm font-bold text-zinc-600 flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="font-semibold text-zinc-900">{{ $user->name }}</div>
                            <div class="text-xs text-zinc-400">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="font-bold {{ $user->balance > 0 ? 'text-emerald-600' : 'text-zinc-400' }}">
                        Rp {{ number_format($user->balance, 0, ',', '.') }}
                    </span>
                </td>
                <td class="px-6 py-4 font-medium text-zinc-700">{{ $user->orders()->count() }} pesanan</td>
                <td class="px-6 py-4 text-xs text-zinc-400 font-medium">{{ $user->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4 text-center">
                    <a href="{{ route('admin.users.show', $user) }}"
                        class="inline-flex items-center gap-1.5 h-9 px-4 rounded-2xl bg-zinc-100 text-zinc-600 text-xs font-bold hover:bg-zinc-200 transition-colors" style="text-decoration:none">
                        <iconify-icon icon="solar:eye-linear" class="text-base"></iconify-icon>
                        Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-16 text-center">
                    <iconify-icon icon="solar:users-group-rounded-linear" class="text-5xl text-zinc-300 block mb-3"></iconify-icon>
                    <p class="text-zinc-400 font-medium">Belum ada pengguna</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-zinc-100">{{ $users->links() }}</div>
    @endif
</div>
@endsection
