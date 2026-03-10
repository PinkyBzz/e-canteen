<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Kantin 40') }}</title>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    @vite(['resources/css/app.css'])
    <style>
        body {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.35s ease, transform 0.35s ease;
        }
        body.ready { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased flex items-start justify-center px-4 py-16 relative">

    {{-- Ambient blobs --}}
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute -top-1/4 -left-1/4 w-2/3 h-2/3 rounded-full bg-indigo-200/30 blur-[120px]"></div>
        <div class="absolute -bottom-1/4 -right-1/4 w-2/3 h-2/3 rounded-full bg-teal-200/25 blur-[100px]"></div>
    </div>

    {{-- Card --}}
    <div class="relative z-10 w-full max-w-md bg-white/70 backdrop-blur-2xl border border-white/80 rounded-[2rem] shadow-2xl shadow-zinc-200/50 p-8 md:p-10">
        {{-- Brand --}}
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-zinc-900 flex items-center justify-center shadow-lg shadow-zinc-900/20 mb-4">
                <iconify-icon icon="solar:cup-hot-bold" style="color:white;font-size:1.6rem;"></iconify-icon>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-zinc-900">Kantin 40</h1>
            <p class="text-sm text-zinc-400 font-medium mt-0.5">Sistem Pre-Order Kantin</p>
        </div>

        {{ $slot }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => document.body.classList.add('ready'));
        });
    </script>
</body>
</html>
