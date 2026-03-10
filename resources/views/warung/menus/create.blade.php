@extends('layouts.warung')
@section('title', 'Tambah Menu')
@section('page-title', 'Tambah Menu Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white/70 backdrop-blur-xl border border-white/80 rounded-3xl shadow-sm p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-2xl bg-amber-500 flex items-center justify-center">
                <iconify-icon icon="solar:add-square-linear" class="text-white text-lg"></iconify-icon>
            </div>
            <h2 class="text-lg font-semibold text-zinc-900">Tambah Menu Baru</h2>
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

        <form method="POST" action="{{ route('warung.menus.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Nama Menu <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Nasi Goreng Spesial"
                        class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="category" value="{{ old('category') }}" required placeholder="Makanan / Minuman" list="categories"
                        class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition">
                    <datalist id="categories">
                        <option value="Makanan"><option value="Minuman"><option value="Snack">
                    </datalist>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Harga <span class="text-red-500">*</span></label>
                    <div class="flex rounded-xl border border-zinc-200 overflow-hidden bg-white/60 focus-within:ring-2 focus-within:ring-amber-400/30 focus-within:border-amber-400 transition">
                        <span class="px-3 py-2.5 bg-zinc-50 text-zinc-500 text-sm border-r border-zinc-200 font-medium">Rp</span>
                        <input type="number" name="price" value="{{ old('price') }}" required min="0" step="500" placeholder="0"
                            class="flex-1 px-3 py-2.5 text-sm bg-transparent focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition">
                        <option value="available" {{ old('status')=='available'?'selected':'' }}>Tersedia</option>
                        <option value="out"       {{ old('status')=='out'?'selected':'' }}>Habis</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Deskripsi singkat menu..."
                    class="w-full rounded-xl border border-zinc-200 bg-white/60 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400 transition resize-none">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Foto Menu</label>
                <div id="previewWrap" class="hidden mb-3">
                    <img id="photoPreview" src="" alt="Preview" class="h-40 w-full object-cover rounded-2xl border border-zinc-200">
                </div>
                <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-amber-200 rounded-2xl cursor-pointer bg-amber-50/40 hover:bg-amber-50/80 transition group">
                    <iconify-icon icon="solar:upload-linear" class="text-2xl text-amber-400 group-hover:text-amber-600 mb-1 transition"></iconify-icon>
                    <span class="text-sm text-zinc-500 group-hover:text-zinc-700 transition">Klik untuk pilih foto</span>
                    <span class="text-xs text-zinc-400 mt-0.5">JPG, PNG, WEBP · maks 2MB</span>
                    <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden">
                </label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition">
                    <iconify-icon icon="solar:check-circle-linear"></iconify-icon>Simpan Menu
                </button>
                <a href="{{ route('warung.menus.index') }}" class="flex items-center gap-2 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-sm font-medium px-5 py-2.5 rounded-xl transition">
                    <iconify-icon icon="solar:close-circle-linear"></iconify-icon>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('photoInput').addEventListener('change', function () {
    const preview = document.getElementById('photoPreview');
    const wrap    = document.getElementById('previewWrap');
    if (this.files && this.files[0]) {
        preview.src = URL.createObjectURL(this.files[0]);
        wrap.classList.remove('hidden');
    }
});
</script>
@endpush
