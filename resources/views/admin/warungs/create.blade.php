    @extends('layouts.admin')
@section('title', 'Tambah Warung')
@section('page-title', 'Tambah Warung')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-2xl bg-zinc-900 flex items-center justify-center">
                <iconify-icon icon="solar:shop-linear" class="text-white text-lg"></iconify-icon>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-zinc-900 leading-tight">Tambah Warung Baru</h2>
                <p class="text-sm text-zinc-500">Buat akun pemilik warung sekaligus profil warung</p>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2 text-red-700 font-medium text-sm">
                <iconify-icon icon="solar:danger-circle-linear"></iconify-icon>Terdapat kesalahan:
            </div>
            <ul class="list-disc list-inside text-red-600 text-sm space-y-0.5">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.warungs.store') }}" class="space-y-5">
            @csrf

            <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest">Akun Pemilik</p>

            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Nama Pemilik <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-400/30 focus:border-zinc-400 transition">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-400/30 focus:border-zinc-400 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="6"
                        class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-400/30 focus:border-zinc-400 transition">
                </div>
            </div>

            <hr class="border-zinc-100">
            <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest">Profil Warung</p>

            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Nama Warung <span class="text-red-500">*</span></label>
                <input type="text" name="warung_name" value="{{ old('warung_name') }}" required
                    class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-400/30 focus:border-zinc-400 transition">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                        class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-400/30 focus:border-zinc-400 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Lokasi / Nomor Meja</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Gedung A, Lantai 1"
                        class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-400/30 focus:border-zinc-400 transition">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="flex items-center gap-2 bg-zinc-900 hover:bg-zinc-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition" style="color:white">
                    <iconify-icon icon="solar:add-circle-linear"></iconify-icon>Buat Warung
                </button>
                <a href="{{ route('admin.warungs.index') }}" class="text-sm text-zinc-500 hover:text-zinc-700 transition" style="text-decoration:none">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
