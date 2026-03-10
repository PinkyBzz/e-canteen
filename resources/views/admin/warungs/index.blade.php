@extends('layouts.admin')
@section('title', 'Kelola Warung')
@section('page-title', 'Kelola Warung')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-zinc-900">Daftar Warung</h1>
            <p class="text-sm text-zinc-500 mt-0.5">{{ $warungs->count() }} warung terdaftar</p>
        </div>
        <a href="{{ route('admin.warungs.create') }}"
            class="flex items-center gap-2 bg-zinc-900 hover:bg-zinc-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition" style="text-decoration:none;color:white">
            <iconify-icon icon="solar:add-circle-linear"></iconify-icon>Tambah Warung
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 flex items-center gap-2 text-emerald-700 text-sm font-medium">
        <iconify-icon icon="solar:check-circle-linear" class="text-lg"></iconify-icon>{{ session('success') }}
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm overflow-hidden">
        @if($warungs->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-zinc-400">
            <iconify-icon icon="solar:shop-linear" class="text-5xl mb-3"></iconify-icon>
            <p class="text-sm font-medium">Belum ada warung terdaftar</p>
            <a href="{{ route('admin.warungs.create') }}" class="mt-3 text-sm text-zinc-600 underline underline-offset-2" style="text-decoration:underline">Tambah sekarang</a>
        </div>
        @else
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-100 bg-zinc-50/60">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">Warung</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">Akun</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">Telepon</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">Lokasi</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-50">
                @foreach($warungs as $warung)
                <tr class="hover:bg-zinc-50/50 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl overflow-hidden bg-amber-50 border border-amber-100 flex items-center justify-center flex-shrink-0">
                                @if($warung->logo)
                                    <img src="{{ asset('storage/'.$warung->logo) }}" alt="{{ $warung->name }}" class="w-full h-full object-cover">
                                @else
                                    <iconify-icon icon="solar:shop-linear" class="text-amber-400"></iconify-icon>
                                @endif
                            </div>
                            <span class="font-medium text-zinc-900">{{ $warung->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <div>
                            <p class="text-zinc-800 font-medium">{{ $warung->user->name }}</p>
                            <p class="text-zinc-400 text-xs">{{ $warung->user->email }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-zinc-600">{{ $warung->phone ?: '—' }}</td>
                    <td class="px-4 py-3.5 text-zinc-600">{{ $warung->address ?: '—' }}</td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $warung->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-zinc-100 text-zinc-500 border border-zinc-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $warung->is_active ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                            {{ $warung->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-2">
                            {{-- Toggle active --}}
                            <form action="{{ route('admin.warungs.toggle', $warung) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" title="{{ $warung->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                    class="w-8 h-8 rounded-xl flex items-center justify-center transition
                                    {{ $warung->is_active ? 'bg-zinc-100 hover:bg-red-50 text-zinc-600 hover:text-red-600' : 'bg-zinc-100 hover:bg-emerald-50 text-zinc-600 hover:text-emerald-600' }}">
                                    <iconify-icon icon="{{ $warung->is_active ? 'solar:pause-circle-linear' : 'solar:play-circle-linear' }}" class="text-base"></iconify-icon>
                                </button>
                            </form>
                            {{-- Delete --}}
                            <form action="{{ route('admin.warungs.destroy', $warung) }}" method="POST"
                                onsubmit="return confirm('Hapus warung {{ addslashes($warung->name) }}? Akun pemilik juga akan dihapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Hapus"
                                    class="w-8 h-8 rounded-xl bg-zinc-100 hover:bg-red-50 text-zinc-600 hover:text-red-600 flex items-center justify-center transition">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-linear" class="text-base"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
