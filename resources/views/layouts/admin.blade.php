<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Admin Panel') - MULTI FITNES CENTRE (MFC) Palopo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-mfc-page font-sans text-mfc-ink antialiased flex min-h-screen overflow-x-hidden">

    <!-- 1. DESKTOP SIDEBAR (Sesuai Desain Figma Column 5 & 6) -->
    <aside class="w-64 shrink-0 bg-mfc-black text-white flex flex-col justify-between hidden md:flex border-r border-neutral-800 sticky top-0 h-screen">
        <div>
            <!-- Sidebar Header / Logo -->
            <div class="h-20 flex items-center gap-3 px-6 border-b border-neutral-800">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-mfc-black font-black shrink-0">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10v4m3-6v8m3-7v6m6-6v6m3-7v8m3-6v4M2 12h20"/>
                    </svg>
                </div>
                <div>
                    <span class="text-sm font-black tracking-wider text-white uppercase block leading-tight">MFC Palopo</span>
                    <span class="text-[10px] tracking-widest text-neutral-400 uppercase font-semibold">Admin Panel</span>
                </div>
            </div>

            <!-- Navigasi Menu -->
            <nav class="p-4 space-y-1.5 text-sm" aria-label="Navigasi Admin Desktop">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white text-mfc-black shadow' : 'text-neutral-300 hover:bg-neutral-900 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.registrations.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-colors {{ request()->routeIs('admin.registrations.*') ? 'bg-white text-mfc-black shadow' : 'text-neutral-300 hover:bg-neutral-900 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Data Pendaftaran
                </a>

                <a href="{{ route('admin.members.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-colors {{ request()->routeIs('admin.members.*') ? 'bg-white text-mfc-black shadow' : 'text-neutral-300 hover:bg-neutral-900 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Data Member
                </a>

                <div class="pt-4 mt-4 border-t border-neutral-800">
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-400 hover:bg-neutral-900 hover:text-white transition-colors">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Buka Website Publik &rarr;
                    </a>
                </div>
            </nav>
        </div>

        <!-- Sidebar Footer / Logout -->
        <div class="p-4 border-t border-neutral-800">
            <div class="px-4 py-3 rounded-xl bg-neutral-900/60 mb-2">
                <p class="text-xs text-neutral-400">Login sebagai:</p>
                <p class="text-sm font-bold text-white truncate">{{ auth()->guard('admin')->user()->username ?? 'Admin' }}</p>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center gap-2 rounded-xl border border-neutral-800 px-4 py-2.5 text-xs font-bold text-neutral-300 hover:bg-neutral-900 hover:text-rose-400 transition-colors">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout Admin
                </button>
            </form>
        </div>
    </aside>

    <!-- 2. MOBILE SIDEBAR DRAWER (Untuk Smartphone & Tablet Kecil) -->
    <div id="admin-mobile-backdrop" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 hidden transition-opacity duration-300 md:hidden" aria-hidden="true"></div>
    
    <aside id="admin-mobile-sidebar" class="fixed inset-y-0 left-0 w-72 max-w-[85vw] bg-mfc-black text-white z-50 flex flex-col justify-between -translate-x-full transition-transform duration-300 ease-in-out border-r border-neutral-800 md:hidden shadow-2xl" aria-label="Navigasi Admin Mobile">
        <div>
            <!-- Header Mobile Drawer -->
            <div class="h-16 sm:h-20 flex items-center justify-between px-5 border-b border-neutral-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-mfc-black font-black shrink-0">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10v4m3-6v8m3-7v6m6-6v6m3-7v8m3-6v4M2 12h20"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xs font-black tracking-wider text-white uppercase block leading-tight">MFC Palopo</span>
                        <span class="text-[9px] tracking-widest text-neutral-400 uppercase font-semibold">Admin Panel</span>
                    </div>
                </div>

                <button id="admin-mobile-close" type="button" aria-label="Tutup Menu"
                        class="flex h-9 w-9 items-center justify-center rounded-xl text-neutral-400 hover:text-white hover:bg-neutral-800 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Menu Links Mobile -->
            <nav class="p-4 space-y-1 text-sm">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 min-h-[44px] rounded-xl font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white text-mfc-black shadow' : 'text-neutral-300 hover:bg-neutral-900 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.registrations.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 min-h-[44px] rounded-xl font-semibold transition-colors {{ request()->routeIs('admin.registrations.*') ? 'bg-white text-mfc-black shadow' : 'text-neutral-300 hover:bg-neutral-900 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Data Pendaftaran
                </a>

                <a href="{{ route('admin.members.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 min-h-[44px] rounded-xl font-semibold transition-colors {{ request()->routeIs('admin.members.*') ? 'bg-white text-mfc-black shadow' : 'text-neutral-300 hover:bg-neutral-900 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Data Member
                </a>

                <div class="pt-4 mt-4 border-t border-neutral-800">
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center gap-3 px-4 py-2.5 min-h-[44px] rounded-xl text-xs font-semibold text-neutral-400 hover:bg-neutral-900 hover:text-white transition-colors">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Buka Website Publik &rarr;
                    </a>
                </div>
            </nav>
        </div>

        <!-- Footer Drawer Mobile -->
        <div class="p-4 border-t border-neutral-800">
            <div class="px-4 py-3 rounded-xl bg-neutral-900/60 mb-2">
                <p class="text-[11px] text-neutral-400">Login sebagai:</p>
                <p class="text-xs font-bold text-white truncate">{{ auth()->guard('admin')->user()->username ?? 'Admin' }}</p>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center gap-2 rounded-xl border border-neutral-800 px-4 py-3 min-h-[44px] text-xs font-bold text-neutral-300 hover:bg-neutral-900 hover:text-rose-400 transition-colors">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout Admin
                </button>
            </form>
        </div>
    </aside>

    <!-- 3. MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Top Header Bar -->
        <header class="h-16 sm:h-20 border-b border-mfc-line bg-white px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <!-- Mobile Sidebar Hamburger Button -->
                <button id="admin-sidebar-toggle" type="button" aria-label="Buka Menu Admin"
                        class="md:hidden flex h-10 w-10 items-center justify-center rounded-xl bg-neutral-100 text-mfc-black hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-neutral-400 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div>
                    <h1 class="text-base sm:text-lg font-black text-mfc-black leading-tight truncate">@yield('page_title', 'Dashboard')</h1>
                    <p class="text-[11px] sm:text-xs text-mfc-mute hidden xs:block">Panel Manajemen MULTI FITNES CENTRE (MFC) Palopo</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                <span class="text-xs text-mfc-mute hidden lg:inline-block">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 sm:h-9 sm:w-9 rounded-full bg-mfc-black text-white flex items-center justify-center text-xs font-bold uppercase shadow-sm">
                        {{ substr(auth()->guard('admin')->user()->username ?? 'A', 0, 1) }}
                    </div>
                    <span class="text-xs font-bold text-mfc-black hidden sm:inline-block">
                        {{ auth()->guard('admin')->user()->username ?? 'Admin' }}
                    </span>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6">
            @if(session('success'))
                <div class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-3.5 sm:p-4 text-emerald-800 shadow-sm" role="alert">
                    <div class="flex items-center gap-2.5 sm:gap-3">
                        <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-xs sm:text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="flex items-center justify-between rounded-xl border border-mfc-error/20 bg-mfc-error-bg p-3.5 sm:p-4 text-mfc-error shadow-sm" role="alert">
                    <div class="flex items-center gap-2.5 sm:gap-3">
                        <svg class="h-5 w-5 shrink-0 text-mfc-error" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-xs sm:text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8 flex-1">
            @yield('admin_content')
        </main>
    </div>

    <!-- 4. MODAL KONFIRMASI HAPUS DATA GLOBAL (Desain Elegan Konsisten MFC) -->
    <div id="delete-modal-backdrop" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 hidden transition-opacity duration-200" aria-hidden="true"></div>

    <div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <div class="w-full max-w-md rounded-2xl sm:rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-neutral-200/80 animate-in fade-in zoom-in-95 duration-150">
            
            <!-- Icon Tempat Sampah Merah -->
            <div class="mx-auto flex h-13 w-13 sm:h-14 sm:w-14 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 border border-rose-100">
                <svg class="h-6 w-6 sm:h-7 sm:w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>

            <div class="mt-4 text-center space-y-2">
                <h3 id="delete-modal-title" class="text-base sm:text-lg font-black text-mfc-black">
                    Konfirmasi Hapus Data
                </h3>
                <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed">
                    Apakah Anda yakin ingin menghapus data <span id="delete-modal-item-type" class="font-semibold text-mfc-black">pendaftaran</span> atas nama <strong id="delete-modal-item-name" class="font-bold text-rose-600"></strong>? Tindakan ini permanen dan tidak dapat dibatalkan.
                </p>
            </div>

            <form id="delete-modal-form" method="POST" action="" class="mt-6 flex flex-col sm:flex-row items-center gap-2.5 sm:gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()" 
                        class="w-full sm:w-1/2 flex items-center justify-center min-h-[44px] rounded-xl border border-mfc-line bg-white px-4 py-2.5 text-xs font-bold text-neutral-700 hover:bg-neutral-100 transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="w-full sm:w-1/2 flex items-center justify-center min-h-[44px] rounded-xl bg-rose-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-rose-700 transition-colors">
                    Ya, Hapus Data
                </button>
            </form>

        </div>
    </div>

    <!-- Script Drawer Admin Mobile & Modal Konfirmasi -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('admin-sidebar-toggle');
            const closeBtn = document.getElementById('admin-mobile-close');
            const mobileSidebar = document.getElementById('admin-mobile-sidebar');
            const backdrop = document.getElementById('admin-mobile-backdrop');

            function openSidebar() {
                if (mobileSidebar && backdrop) {
                    backdrop.classList.remove('hidden');
                    setTimeout(() => {
                        backdrop.classList.add('opacity-100');
                        mobileSidebar.classList.remove('-translate-x-full');
                    }, 10);
                    document.body.classList.add('overflow-hidden');
                }
            }

            function closeSidebar() {
                if (mobileSidebar && backdrop) {
                    mobileSidebar.classList.add('-translate-x-full');
                    backdrop.classList.remove('opacity-100');
                    setTimeout(() => {
                        backdrop.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }, 300);
                }
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // Handler Modal Hapus Global
            window.openDeleteModal = function(actionUrl, itemName, itemType = 'pendaftaran') {
                const modal = document.getElementById('delete-modal');
                const modalBackdrop = document.getElementById('delete-modal-backdrop');
                const form = document.getElementById('delete-modal-form');
                const nameEl = document.getElementById('delete-modal-item-name');
                const typeEl = document.getElementById('delete-modal-item-type');

                if (modal && modalBackdrop && form) {
                    form.action = actionUrl;
                    if (nameEl) nameEl.textContent = itemName;
                    if (typeEl) typeEl.textContent = itemType;

                    modalBackdrop.classList.remove('hidden');
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
            };

            window.closeDeleteModal = function() {
                const modal = document.getElementById('delete-modal');
                const modalBackdrop = document.getElementById('delete-modal-backdrop');

                if (modal && modalBackdrop) {
                    modal.classList.add('hidden');
                    modalBackdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            };

            const deleteBackdrop = document.getElementById('delete-modal-backdrop');
            if (deleteBackdrop) {
                deleteBackdrop.addEventListener('click', window.closeDeleteModal);
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeSidebar();
                    if (window.closeDeleteModal) window.closeDeleteModal();
                }
            });
        });
    </script>

</body>
</html>
