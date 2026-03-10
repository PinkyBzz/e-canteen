@extends('layouts.warung')
@section('title', 'Profil Warung')
@section('page-title', 'Pengaturan Warung')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-2xl bg-amber-500 flex items-center justify-center">
                <iconify-icon icon="solar:settings-linear" class="text-white text-lg"></iconify-icon>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-zinc-900 leading-tight">Profil Warung</h2>
                <p class="text-sm text-zinc-500">Kelola informasi warung Anda</p>
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

        <form method="POST" action="{{ route('warung.settings.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PATCH')

            {{-- Logo --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-2">Logo Warung</label>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl overflow-hidden border border-zinc-200 bg-zinc-50 flex items-center justify-center flex-shrink-0">
                        @if($warung->logo)
                        <img id="logoPreview" src="{{ asset('storage/'.$warung->logo) }}" alt="{{ $warung->name }}" class="w-full h-full object-cover">
                        @else
                        <iconify-icon id="logoPlaceholder" icon="solar:shop-linear" class="text-2xl text-zinc-300"></iconify-icon>
                        <img id="logoPreview" src="" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                    <label class="flex flex-col items-center justify-center flex-1 h-20 border-2 border-dashed border-amber-200 rounded-2xl cursor-pointer bg-amber-50/40 hover:bg-amber-50/80 transition group">
                        <iconify-icon icon="solar:upload-linear" class="text-xl text-amber-400 group-hover:text-amber-600 mb-0.5 transition"></iconify-icon>
                        <span class="text-xs text-zinc-500 group-hover:text-zinc-700 transition">Upload logo baru</span>
                        <input type="file" name="logo" id="logoInput" accept="image/*" class="hidden">
                    </label>
                </div>
            </div>

            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Nama Warung <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $warung->name) }}" required
                    class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition">
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Deskripsi Warung</label>
                <textarea name="description" rows="3"
                    class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition resize-none">{{ old('description', $warung->description) }}</textarea>
            </div>

            {{-- Phone + Address --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $warung->phone) }}" placeholder="08xxxxxxxxxx"
                        class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Lokasi / Nomor Meja</label>
                    <input type="text" name="address" value="{{ old('address', $warung->address) }}" placeholder="Contoh: Gedung A, Lantai 1"
                        class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition">
                    <iconify-icon icon="solar:check-circle-linear"></iconify-icon>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('logoInput').addEventListener('change', function () {
    const preview     = document.getElementById('logoPreview');
    const placeholder = document.getElementById('logoPlaceholder');
    if (this.files && this.files[0]) {
        preview.src = URL.createObjectURL(this.files[0]);
        preview.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
    }
});
</script>
@endpush
