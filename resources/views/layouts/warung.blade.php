<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Warung') — Kantin 40</title>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    @vite(['resources/css/app.css'])
    <style>
        body { opacity:0; transition: opacity 0.25s ease; }
        body.ready { opacity:1; }
        body.leaving { opacity:0 !important; }
        .nav-progress {
            position:fixed; top:0; left:0; height:2px; width:0%;
            background: linear-gradient(90deg,#f59e0b,#d97706);
            transition: width 0.4s ease; z-index:9999; border-radius:9999px;
        }
        body.leaving .nav-progress { width:70%; }
    </style>
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-amber-50 via-stone-50 to-orange-50 min-h-screen text-zinc-900 antialiased" onload="document.body.classList.add('ready')">

{{-- Ambient blobs --}}
<div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
    <div class="absolute -top-32 -left-32 w-[480px] h-[480px] rounded-full bg-amber-200/30 blur-3xl"></div>
    <div class="absolute top-1/2 -translate-y-1/2 -right-40 w-[520px] h-[520px] rounded-full bg-orange-200/20 blur-3xl"></div>
    <div class="absolute bottom-0 left-1/3 w-[400px] h-[400px] rounded-full bg-stone-200/40 blur-3xl"></div>
</div>

<div class="nav-progress" id="nav-progress"></div>

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white/60 backdrop-blur-2xl border-r border-white/70 flex flex-col transition-transform duration-300 -translate-x-full md:translate-x-0 shadow-2xl shadow-amber-200/30">
        {{-- Brand --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/50">
            <div class="w-9 h-9 rounded-xl bg-amber-500 flex items-center justify-center shadow">
                <iconify-icon icon="solar:shop-bold" style="color:white;font-size:1.2rem;"></iconify-icon>
            </div>
            <div>
                <div class="font-extrabold text-zinc-900 tracking-tight leading-none truncate max-w-[140px]">{{ auth()->user()->warung->name ?? 'Warung' }}</div>
                <div class="text-[10px] text-zinc-400 font-semibold uppercase tracking-widest mt-0.5">Dapur Panel</div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest px-3 mb-2">Overview</p>
            <a href="{{ route('warung.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm font-semibold transition-all duration-200 active:scale-95 {{ request()->routeIs('warung.dashboard') ? 'bg-white/90 text-zinc-900 shadow-sm border border-white/80' : 'text-zinc-500 hover:bg-white/80 hover:text-zinc-900 hover:shadow-sm hover:border hover:border-white/70' }}"
                style="text-decoration:none">
                <iconify-icon icon="solar:widget-5-linear" class="text-lg flex-shrink-0"></iconify-icon>
                Dashboard
            </a>

            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest px-3 pt-4 mb-2">Kelola</p>
            <a href="{{ route('warung.menus.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm font-semibold transition-all duration-200 active:scale-95 {{ request()->routeIs('warung.menus.*') ? 'bg-white/90 text-zinc-900 shadow-sm border border-white/80' : 'text-zinc-500 hover:bg-white/80 hover:text-zinc-900 hover:shadow-sm hover:border hover:border-white/70' }}"
                style="text-decoration:none">
                <iconify-icon icon="solar:hamburger-menu-linear" class="text-lg flex-shrink-0"></iconify-icon>
                Menu Warung
            </a>
            <a href="{{ route('warung.orders.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm font-semibold transition-all duration-200 active:scale-95 {{ request()->routeIs('warung.orders.*') ? 'bg-white/90 text-zinc-900 shadow-sm border border-white/80' : 'text-zinc-500 hover:bg-white/80 hover:text-zinc-900 hover:shadow-sm hover:border hover:border-white/70' }}"
                style="text-decoration:none">
                <iconify-icon icon="solar:bill-list-linear" class="text-lg flex-shrink-0"></iconify-icon>
                Antrean Pesanan
                @php
                    $warung = auth()->user()->warung;
                    $mIds   = $warung ? $warung->menus()->pluck('id') : collect();
                    $pendingCnt = $mIds->count() ? \App\Models\Order::whereHas('items', fn($q)=>$q->whereIn('menu_id',$mIds))->whereIn('status',['pending','preparing'])->whereDate('created_at',today())->count() : 0;
                @endphp
                @if($pendingCnt > 0)
                <span class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-700">{{ $pendingCnt }}</span>
                @endif
            </a>

            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest px-3 pt-4 mb-2">Laporan</p>
            <a href="{{ route('warung.reports.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm font-semibold transition-all duration-200 active:scale-95 {{ request()->routeIs('warung.reports.*') ? 'bg-white/90 text-zinc-900 shadow-sm border border-white/80' : 'text-zinc-500 hover:bg-white/80 hover:text-zinc-900 hover:shadow-sm hover:border hover:border-white/70' }}"
                style="text-decoration:none">
                <iconify-icon icon="solar:chart-linear" class="text-lg flex-shrink-0"></iconify-icon>
                Laporan Pendapatan
            </a>

            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest px-3 pt-4 mb-2">Pengaturan</p>
            <a href="{{ route('warung.settings') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-2xl text-sm font-semibold transition-all duration-200 active:scale-95 {{ request()->routeIs('warung.settings*') ? 'bg-white/90 text-zinc-900 shadow-sm border border-white/80' : 'text-zinc-500 hover:bg-white/80 hover:text-zinc-900 hover:shadow-sm hover:border hover:border-white/70' }}"
                style="text-decoration:none">
                <iconify-icon icon="solar:settings-linear" class="text-lg flex-shrink-0"></iconify-icon>
                Profil Warung
            </a>
        </nav>

        {{-- User Footer --}}
        <div class="px-4 py-4 border-t border-white/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-amber-100 border border-amber-200 flex items-center justify-center text-sm font-bold text-amber-700 flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-zinc-800 truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-zinc-400 truncate">{{ auth()->user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl text-zinc-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Logout">
                        <iconify-icon icon="solar:logout-2-linear" class="text-lg"></iconify-icon>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Sidebar Overlay (mobile) --}}
    <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-zinc-900/20 backdrop-blur-sm hidden md:hidden" onclick="closeSidebar()"></div>

    {{-- Main --}}
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">
        {{-- Topbar --}}
        <header class="sticky top-0 z-30 bg-white/50 backdrop-blur-xl border-b border-white/60 px-4 md:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button onclick="openSidebar()" class="md:hidden w-10 h-10 rounded-2xl bg-white/70 flex items-center justify-center text-zinc-600 hover:bg-white/90 transition-colors">
                    <iconify-icon icon="solar:hamburger-menu-linear" class="text-xl"></iconify-icon>
                </button>
                <h1 class="font-bold text-zinc-900 text-base">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:block text-sm font-medium text-zinc-400">{{ now()->isoFormat('D MMM YYYY') }}</span>
                @if($pendingCnt > 0)
                <a href="{{ route('warung.orders.index') }}"
                   class="flex items-center gap-1.5 text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-xl hover:bg-amber-200 transition">
                    <iconify-icon icon="solar:bell-bing-linear" class="text-sm"></iconify-icon>
                    {{ $pendingCnt }} pesanan baru
                </a>
                @endif
            </div>
        </header>

        {{-- Flash --}}
        @if(session('success') || session('error'))
        <div class="px-4 md:px-8 pt-4">
            @if(session('success'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
                <iconify-icon icon="solar:check-circle-bold" class="text-lg flex-shrink-0"></iconify-icon>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
                <iconify-icon icon="solar:danger-circle-bold" class="text-lg flex-shrink-0"></iconify-icon>
                {{ session('error') }}
            </div>
            @endif
        </div>
        @endif

        {{-- Content --}}
        <main class="flex-1 px-4 md:px-8 py-6">
            @yield('content')
        </main>

        <footer class="px-8 py-4 border-t border-white/40 text-center text-xs text-zinc-400 font-medium">
            &copy; {{ date('Y') }} Kantin 40 — Panel Warung
        </footer>
    </div>
</div>

<script>
function openSidebar() {
    document.getElementById('sidebar').classList.remove('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.remove('hidden');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.add('hidden');
}

document.addEventListener('click', function (e) {
    const link = e.target.closest('a[href]');
    if (!link) return;
    const href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript') ||
        e.ctrlKey || e.metaKey || e.shiftKey || e.altKey ||
        link.target === '_blank') return;
    try {
        const url = new URL(href, location.href);
        if (url.origin !== location.origin) return;
    } catch { return; }
    e.preventDefault();
    document.body.classList.add('leaving');
    setTimeout(() => { location.href = href; }, 250);
});
</script>

<script>
/* ── Custom Select Dropdowns ── */
(function () {
  const CHEVRON = `<svg style="width:14px;height:14px;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="#71717a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>`;

  function buildSelects() {
    document.querySelectorAll('select:not([data-cs])').forEach(function (sel) {
      sel.setAttribute('data-cs', '1');

      var wrap = document.createElement('div');
      wrap.style.cssText = 'position:relative;display:inline-block;width:100%';
      sel.parentNode.insertBefore(wrap, sel);
      wrap.appendChild(sel);
      sel.style.display = 'none';

      var btn = document.createElement('div');
      btn.style.cssText = 'display:flex;align-items:center;justify-content:space-between;gap:8px;cursor:pointer;user-select:none;' +
        'background:rgba(255,255,255,.8);border:1px solid #e4e4e7;border-radius:16px;' +
        'padding:0 14px;height:40px;font-size:14px;color:#3f3f46;transition:border-color .15s,box-shadow .15s;min-width:0;';
      btn.innerHTML = '<span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"></span>' + CHEVRON;

      var list = document.createElement('div');
      list.style.cssText = 'display:none;position:absolute;top:calc(100% + 6px);left:0;min-width:100%;z-index:9999;' +
        'background:#fff;border:1px solid #e4e4e7;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,.1);overflow:hidden;';

      function refresh() {
        var opt = sel.options[sel.selectedIndex];
        btn.querySelector('span').textContent = opt ? opt.text : '';
        list.querySelectorAll('[data-cs-item]').forEach(function (item) {
          var isActive = item.dataset.csVal === sel.value;
          item.style.background = isActive ? '#f4f4f5' : '';
          item.style.fontWeight = isActive ? '600' : '400';
          item.style.color = isActive ? '#18181b' : '#3f3f46';
        });
      }

      Array.from(sel.options).forEach(function (o) {
        var item = document.createElement('div');
        item.setAttribute('data-cs-item', '1');
        item.dataset.csVal = o.value;
        item.textContent = o.text;
        item.style.cssText = 'padding:10px 16px;font-size:13.5px;cursor:pointer;transition:background .1s;color:#3f3f46;';
        item.addEventListener('mouseenter', function () { item.style.background = '#f4f4f5'; });
        item.addEventListener('mouseleave', function () {
          item.style.background = item.dataset.csVal === sel.value ? '#f4f4f5' : '';
        });
        item.addEventListener('mousedown', function (e) {
          e.preventDefault();
          sel.value = o.value;
          sel.dispatchEvent(new Event('change', { bubbles: true }));
          closeList();
          refresh();
        });
        list.appendChild(item);
      });

      function openList() {
        document.querySelectorAll('[data-cs-list-open]').forEach(function (l) {
          l.removeAttribute('data-cs-list-open');
          l.style.display = 'none';
          l.previousSibling && l.previousSibling.style && (l.previousSibling.style.borderColor = '#e4e4e7', l.previousSibling.style.boxShadow = '');
        });
        list.setAttribute('data-cs-list-open', '1');
        list.style.display = 'block';
        btn.style.borderColor = '#a1a1aa';
        btn.style.boxShadow = '0 0 0 3px rgba(161,161,170,.2)';
        var rect = list.getBoundingClientRect();
        if (rect.bottom > window.innerHeight) {
          list.style.top = 'auto';
          list.style.bottom = 'calc(100% + 6px)';
        }
      }

      function closeList() {
        list.removeAttribute('data-cs-list-open');
        list.style.display = 'none';
        btn.style.borderColor = '#e4e4e7';
        btn.style.boxShadow = '';
      }

      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        list.hasAttribute('data-cs-list-open') ? closeList() : openList();
      });

      document.addEventListener('click', closeList);

      refresh();
      wrap.appendChild(btn);
      wrap.appendChild(list);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', buildSelects);
  } else {
    buildSelects();
  }

  window._csInit = buildSelects;
})();
</script>

@stack('scripts')
</body>
</html>
