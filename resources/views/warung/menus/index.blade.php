@extends('layouts.warung')
@section('title', 'Menu Warung')
@section('page-title', 'Menu Warung')

@section('content')
{{-- Header row --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
    <form method="GET" class="flex flex-wrap gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu..."
            class="rounded-xl border border-zinc-200 bg-white/70 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition w-44">
        <input type="text" name="category" value="{{ request('category') }}" placeholder="Kategori..."
            class="rounded-xl border border-zinc-200 bg-white/70 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition w-36" list="cat-list">
        <datalist id="cat-list">
            @foreach($categories as $cat)<option value="{{ $cat }}">@endforeach
        </datalist>
        <select name="status" class="rounded-xl border border-zinc-200 bg-white/70 px-4 py-2 text-sm focus:outline-none transition">
            <option value="">Semua Status</option>
            <option value="available" {{ request('status')=='available'?'selected':'' }}>Tersedia</option>
            <option value="out"       {{ request('status')=='out'?'selected':'' }}>Habis</option>
        </select>
        <button type="submit" class="flex items-center gap-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-sm font-medium px-4 py-2 rounded-xl transition">
            <iconify-icon icon="solar:magnifer-linear"></iconify-icon> Cari
        </button>
        @if(request()->anyFilled(['search','category','status']))
        <a href="{{ route('warung.menus.index') }}" class="flex items-center gap-1 text-xs text-zinc-400 hover:text-zinc-700 px-3 py-2 rounded-xl transition">
            <iconify-icon icon="solar:close-circle-linear"></iconify-icon> Reset
        </a>
        @endif
    </form>
    <a href="{{ route('warung.menus.create') }}"
        class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition shadow-sm flex-shrink-0" style="color:white">
        <iconify-icon icon="solar:add-square-linear"></iconify-icon>
        Tambah Menu
    </a>
</div>

{{-- Table --}}
<div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-zinc-50/80 text-zinc-500 text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-medium">Menu</th>
                    <th class="px-4 py-3 text-left font-medium">Kategori</th>
                    <th class="px-4 py-3 text-right font-medium">Harga</th>
                    <th class="px-4 py-3 text-center font-medium">Status</th>
                    <th class="px-5 py-3 text-right font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @forelse($menus as $menu)
                <tr class="hover:bg-zinc-50/50 transition">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            @if($menu->photo)
                            <img src="{{ asset('storage/'.$menu->photo) }}" alt="{{ $menu->name }}" class="w-10 h-10 rounded-2xl object-cover flex-shrink-0 border border-zinc-100">
                            @else
                            <div class="w-10 h-10 rounded-2xl bg-zinc-100 flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="solar:gallery-linear" class="text-zinc-400"></iconify-icon>
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-zinc-900">{{ $menu->name }}</p>
                                <p class="text-xs text-zinc-400 truncate max-w-[180px]">{{ $menu->description ?: '—' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs bg-zinc-100 text-zinc-600 px-2.5 py-0.5 rounded-full font-medium">{{ $menu->category }}</span>
                    </td>
                    <td class="px-4 py-3 text-right font-semibold text-zinc-900">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center">
                        <form method="POST" action="{{ route('warung.menus.toggle-status', $menu) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="text-xs font-semibold px-3 py-1 rounded-full transition {{ $menu->status === 'available' ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-red-100 text-red-500 hover:bg-red-200' }}">
                                {{ $menu->status === 'available' ? 'Tersedia' : 'Habis' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('warung.menus.edit', $menu) }}"
                                class="w-8 h-8 rounded-xl bg-zinc-100 hover:bg-amber-100 hover:text-amber-600 text-zinc-500 flex items-center justify-center transition" title="Edit">
                                <iconify-icon icon="solar:pen-linear"></iconify-icon>
                            </a>
                            <form method="POST" action="{{ route('warung.menus.destroy', $menu) }}" onsubmit="return confirm('Hapus menu {{ addslashes($menu->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-xl bg-zinc-100 hover:bg-red-100 hover:text-red-500 text-zinc-500 flex items-center justify-center transition" title="Hapus">
                                    <iconify-icon icon="solar:trash-bin-2-linear"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-zinc-400">
                    Belum ada menu. <a href="{{ route('warung.menus.create') }}" class="text-amber-600 underline">Tambah sekarang</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($menus->hasPages())
    <div class="px-5 py-4 border-t border-zinc-100">
        {{ $menus->links() }}
    </div>
    @endif
</div>
@endsection
