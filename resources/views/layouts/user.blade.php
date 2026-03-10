<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kantin 40')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    @vite(['resources/css/app.css'])
    <style>
        /* Flash alerts */
        .ec-alert {
            display: flex; align-items: center; gap: .6rem;
            padding: .75rem 1rem; border-radius: 12px;
            font-size: .875rem; font-weight: 500; margin-bottom: .5rem;
        }
        .ec-alert-success { background: rgba(22,163,74,.1); border: 1px solid rgba(22,163,74,.2); color: #15803d; }
        .ec-alert-danger  { background: rgba(239,68,68,.1);  border: 1px solid rgba(239,68,68,.2);  color: #dc2626; }
        
        /* Smooth Page Transition */
        body {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        body.page-enter-active {
            opacity: 1;
            transform: translateY(0);
        }
        body.page-leave-active {
            opacity: 0;
            transform: translateY(-12px);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-zinc-50 text-zinc-900 antialiased relative min-h-screen overflow-x-hidden selection:bg-zinc-200 selection:text-zinc-900">

<!-- Ambient Background for Glassmorphism effect -->
<div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-gradient-to-br from-indigo-100/40 to-purple-100/40 blur-[100px] opacity-70 animate-[pulse_8s_ease-in-out_infinite_alternate]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-tl from-teal-100/40 to-emerald-100/40 blur-[120px] opacity-60 animate-[pulse_12s_ease-in-out_infinite_alternate]"></div>
</div>

<!-- Topbar -->
<header class="sticky top-0 z-40 w-full bg-white/40 backdrop-blur-xl border-b border-white/60 shadow-[0_1px_2px_rgba(0,0,0,0.02)] transition-all duration-300">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <!-- Logo / Brand -->
        <a href="{{ route('user.home') }}" class="flex items-center gap-2 text-decoration-none">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-zinc-800 to-zinc-950 flex items-center justify-center shadow-inner shadow-white/20">
                <iconify-icon icon="solar:cup-hot-linear" style="color: white;" stroke-width="1.5" class="text-lg"></iconify-icon>
            </div>
            <h1 class="font-semibold text-lg tracking-tighter text-zinc-900 mb-0">Kantin 40</h1>
        </a>

        <!-- Desktop Nav Links -->
        <div class="hidden md:flex items-center gap-1 absolute left-1/2 -translate-x-1/2" id="desktop-nav">
            <div id="nav-pill" class="absolute left-0 top-0 h-full bg-white/60 backdrop-blur-xl shadow-sm border border-white/80 rounded-2xl transition-all duration-300 ease-out z-0 pointer-events-none"></div>
            
            <a href="{{ route('user.home') }}" class="nav-link relative z-10 px-4 py-2 rounded-2xl text-sm font-medium transition-colors duration-300 {{ request()->routeIs('user.home') ? 'active-route text-zinc-900' : 'text-zinc-500 hover:text-zinc-900' }}">Menu</a>
            <a href="{{ route('user.orders.history') }}" class="nav-link relative z-10 px-4 py-2 rounded-2xl text-sm font-medium transition-colors duration-300 {{ request()->routeIs('user.orders.*') ? 'active-route text-zinc-900' : 'text-zinc-500 hover:text-zinc-900' }}">Pesanan</a>
            <a href="{{ route('user.profile') }}" class="nav-link relative z-10 px-4 py-2 rounded-2xl text-sm font-medium transition-colors duration-300 {{ request()->routeIs('user.profile') ? 'active-route text-zinc-900' : 'text-zinc-500 hover:text-zinc-900' }}">Profil</a>
        </div>
        
        <!-- Right side -->
        <div class="flex items-center gap-2 sm:gap-4">
            <!-- Saldo - Mobile: Icon only | Desktop: Full display -->
            <div class="flex items-center gap-2 px-2.5 sm:px-4 py-1.5 rounded-full bg-white/60 backdrop-blur-md border border-white/80 shadow-sm">
                <iconify-icon icon="solar:wallet-money-linear" class="text-zinc-500 text-base sm:text-base" stroke-width="1.5"></iconify-icon>
                <div class="flex flex-col">
                    <span class="hidden sm:block text-xs text-zinc-500 font-medium leading-none">Saldo</span>
                    <span class="text-xs sm:text-sm font-semibold text-zinc-900 leading-tight">Rp {{ number_format(auth()->user()->balance ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <!-- Cart Button -->
            @php $cartCount = array_sum(array_column(session()->get('cart', []), 'quantity')); @endphp
            <a href="{{ route('user.cart.index') }}" class="w-10 h-10 rounded-full bg-white/60 backdrop-blur-md border border-white/80 shadow-sm flex items-center justify-center hover:bg-white/80 transition-colors relative group text-decoration-none">
                <iconify-icon icon="solar:cart-large-2-linear" class="text-zinc-700 text-xl group-hover:scale-110 transition-transform duration-300" stroke-width="1.5"></iconify-icon>
                @if($cartCount > 0)
                <span id="navbar-cart-badge" class="absolute top-0 right-0 w-4 h-4 bg-zinc-900 text-white rounded-full border-2 border-white flex items-center justify-center" style="font-size: 0.5rem; font-weight: bold; right: -4px; top: -4px;">{{ $cartCount }}</span>
                @else
                <span id="navbar-cart-badge" class="absolute top-0 right-0 w-3 h-3 bg-zinc-900 rounded-full border-2 border-white d-none"></span>
                @endif
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="w-10 h-10 rounded-full bg-white/60 backdrop-blur-md border border-white/80 shadow-sm flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-colors text-zinc-700">
                    <iconify-icon icon="solar:logout-2-linear" class="text-xl" stroke-width="1.5"></iconify-icon>
                </button>
            </form>
        </div>
    </div>
</header>

{{-- Flash messages --}}
@if(session('success') || session('error') || $errors->any())
<div class="container-xl px-3 mt-3">
    @if(session('success'))
    <div class="ec-alert ec-alert-success">
        <i class="bi bi-check-circle-fill flex-shrink-0"></i>{{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="ec-alert ec-alert-danger">
        <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>{{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="ec-alert ec-alert-danger" style="align-items:flex-start">
        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
        <ul class="mb-0 ps-2">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif
</div>
@endif

{{-- Main content --}}
<main>
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@vite(['resources/js/app.js'])
@stack('scripts')

<script>
document.querySelectorAll('.spotlight-card').forEach(card => {
    card.addEventListener('mousemove', e => {
        const r = card.getBoundingClientRect();
        card.style.setProperty('--mx', `${e.clientX - r.left}px`);
        card.style.setProperty('--my', `${e.clientY - r.top}px`);
    });
});

// Magic Nav Pill Effect
document.addEventListener('DOMContentLoaded', () => {
    const navContainer = document.getElementById('desktop-nav');
    if (!navContainer) return;
    
    const pill = document.getElementById('nav-pill');
    const links = navContainer.querySelectorAll('a.nav-link');
    
    let activeLink = Array.from(links).find(link => link.classList.contains('active-route'));
    
    function movePill(link, animate = true) {
        if (!link) {
            pill.style.opacity = '0';
            return;
        }
        
        pill.style.opacity = '1';
        if(!animate) pill.style.transition = 'none';
        else pill.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        
        const rect = link.getBoundingClientRect();
        const containerRect = navContainer.getBoundingClientRect();
        
        // Calculate relative pos
        const offsetLeft = rect.left - containerRect.left;
        const offsetTop = rect.top - containerRect.top;

        // Use transform for smooth hardware-accelerated movement
        pill.style.width = `${rect.width}px`;
        pill.style.height = `${rect.height}px`;
        pill.style.transform = `translate(${offsetLeft}px, ${offsetTop}px)`;
    }

    // Initialize without animation
    if(activeLink) {
        setTimeout(() => movePill(activeLink, false), 50);
    } else {
        pill.style.opacity = '0';
    }

    links.forEach(link => {
        link.addEventListener('mouseenter', () => movePill(link));
    });

    navContainer.addEventListener('mouseleave', () => {
        if(activeLink) {
            movePill(activeLink);
        } else {
            pill.style.opacity = '0';
        }
    });
});

// Smooth Page Transition
document.addEventListener('DOMContentLoaded', () => {
    // Trigger enter animation
    requestAnimationFrame(() => {
        document.body.classList.add('page-enter-active');
    });

    // Intercept link clicks for leave animation
    document.body.addEventListener('click', e => {
        const link = e.target.closest('a');
        if (!link) return;
        
        const href = link.getAttribute('href');
        const target = link.getAttribute('target');
        
        // Ignore cases where we shouldn't transition
        if (!href || 
            href.startsWith('#') || 
            href.startsWith('javascript:') || 
            target === '_blank' ||
            e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) {
            return;
        }

        // Only transition for the same origin
        try {
            const url = new URL(link.href);
            if (url.origin !== window.location.origin) return;
            
            // Prevent default navigation
            e.preventDefault();
            
            // Trigger leave animation
            document.body.classList.remove('page-enter-active');
            document.body.classList.add('page-leave-active');
            
            // Navigate after transition finishes
            setTimeout(() => {
                window.location.href = href;
            }, 300); // 300ms matches fluid UX
        } catch (err) {
            // Invalid URL, let browser handle it
        }
    });
});

// Cart Badge Live Update
window.updateCartBadge = function(delta = 1) {
    const badge = document.getElementById('navbar-cart-badge');
    if (!badge) return;
    const current = parseInt(badge.dataset.count || badge.textContent.trim()) || 0;
    const next = Math.max(0, current + delta);
    badge.dataset.count = next;
    if (next > 0) {
        badge.textContent = next > 99 ? '99+' : next;
        badge.style.display = 'flex';
        badge.style.width = 'auto';
        badge.style.minWidth = '1.1rem';
        badge.style.height = '1.1rem';
        badge.style.padding = '0 3px';
        badge.style.fontSize = '0.5rem';
        badge.style.fontWeight = 'bold';
        badge.classList.remove('d-none');
        // Pop animation
        badge.style.transform = 'scale(1.4)';
        setTimeout(() => badge.style.transform = 'scale(1)', 200);
    } else {
        badge.classList.add('d-none');
    }
};
// Ensure transition on badge
document.addEventListener('DOMContentLoaded', () => {
    const badge = document.getElementById('navbar-cart-badge');
    if (badge) {
        badge.style.transition = 'transform 0.2s cubic-bezier(0.34,1.56,0.64,1)';
        const initial = parseInt(badge.textContent.trim()) || 0;
        badge.dataset.count = initial;
    }
});

// Handle Back/Forward Cache
window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        document.body.classList.remove('page-leave-active');
        document.body.classList.add('page-enter-active');
    }
});
</script>
</body>
</html>
