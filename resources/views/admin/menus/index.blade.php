@extends('layouts.admin')
@section('title', 'Manajemen Menu')
@section('page-title', 'Manajemen Menu')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-zinc-900">Daftar Menu</h2>
        <p class="text-sm text-zinc-400 font-medium mt-0.5">Kelola semua menu makanan dan minuman kantin</p>
    </div>
    <a href="{{ route('admin.menus.create') }}"
        class="flex items-center gap-2 h-10 px-5 rounded-2xl bg-zinc-900 text-white text-sm font-bold hover:bg-zinc-700 transition-colors shadow-md" style="text-decoration:none;color:white">
        <iconify-icon icon="solar:add-circle-linear" class="text-lg"></iconify-icon>
        Tambah Menu
    </a>
</div>

{{-- Filter --}}
<div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl p-5 shadow-sm mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <input type="text" name="search" placeholder="Cari nama menu..." value="{{ request('search') }}"
            class="h-10 px-4 rounded-2xl border border-zinc-200 bg-white/80 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-200 transition-all flex-grow min-w-[160px]">
        <select name="category" class="h-10 px-4 rounded-2xl border border-zinc-200 bg-white/80 text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-200 transition-all">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <select name="status" class="h-10 px-4 rounded-2xl border border-zinc-200 bg-white/80 text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-200 transition-all">
            <option value="">Semua Status</option>
            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
            <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Habis</option>
        </select>
        <button type="submit" class="h-10 px-5 rounded-2xl bg-zinc-900 text-white text-sm font-bold hover:bg-zinc-700 transition-colors">Filter</button>
        <a href="{{ route('admin.menus.index') }}" class="h-10 px-5 rounded-2xl bg-zinc-100 text-zinc-600 text-sm font-semibold hover:bg-zinc-200 transition-colors flex items-center" style="text-decoration:none">Reset</a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-zinc-100">
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">#</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Menu</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Kategori</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Harga</th>
                <th class="text-left px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Status</th>
                <th class="text-center px-6 py-4 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-50">
            @forelse($menus as $menu)
            <tr class="hover:bg-zinc-50/60 transition-colors">
                <td class="px-6 py-4 text-zinc-400 font-medium">{{ $loop->iteration }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if($menu->photo)
                            <img src="{{ asset('storage/' . $menu->photo) }}" alt="{{ $menu->name }}"
                                class="w-12 h-12 rounded-2xl object-cover border border-zinc-100">
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-zinc-100 flex items-center justify-center">
                                <iconify-icon icon="solar:plate-linear" class="text-xl text-zinc-400"></iconify-icon>
                            </div>
                        @endif
                        <div>
                            <div class="font-semibold text-zinc-900">{{ $menu->name }}</div>
                            @if($menu->description)
                            <div class="text-xs text-zinc-400 mt-0.5">{{ Str::limit($menu->description, 50) }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 bg-zinc-100 text-zinc-600 rounded-full text-xs font-semibold">{{ $menu->category }}</span>
                </td>
                <td class="px-6 py-4 font-bold text-zinc-900">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                <td class="px-6 py-4">
                    <form method="POST" action="{{ route('admin.menus.toggle-status', $menu) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="px-3 py-1.5 rounded-full text-xs font-bold transition-all hover:opacity-80 {{ $menu->status === 'available' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                            {{ $menu->status === 'available' ? '● Tersedia' : '● Habis' }}
                        </button>
                    </form>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.menus.edit', $menu) }}"
                            class="w-9 h-9 rounded-2xl bg-zinc-100 flex items-center justify-center text-zinc-600 hover:bg-zinc-200 transition-colors" style="text-decoration:none" title="Edit">
                            <iconify-icon icon="solar:pen-linear" class="text-base"></iconify-icon>
                        </a>
                        <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}"
                            onsubmit="return confirm('Hapus menu {{ addslashes($menu->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-9 h-9 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100 transition-colors" title="Hapus">
                                <iconify-icon icon="solar:trash-bin-2-linear" class="text-base"></iconify-icon>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-16 text-center">
                    <iconify-icon icon="solar:inbox-linear" class="text-5xl text-zinc-300 block mb-3"></iconify-icon>
                    <p class="text-zinc-400 font-medium">Belum ada menu.</p>
                    <a href="{{ route('admin.menus.create') }}" class="text-sm font-bold text-zinc-900 hover:underline" style="text-decoration:none">Tambah menu pertama</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($menus->hasPages())
    <div class="px-6 py-4 border-t border-zinc-100">
        {{ $menus->links() }}
    </div>
    @endif
</div>
@endsection
