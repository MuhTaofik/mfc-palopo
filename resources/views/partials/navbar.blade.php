<header class="sticky top-0 z-40 w-full bg-[#1c1d1d] text-white border-b border-neutral-800 transition-all">
    <div class="mx-auto flex h-16 sm:h-18 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        
        <!-- Logo Brand (Sesuai Figma: Ikon Dumbbell + MFC) -->
        <a href="{{ route('home') }}" class="group flex items-center gap-2.5 py-2">
            <svg class="h-6 w-6 text-white shrink-0" viewBox="0 0 24 24" fill="currentColor">
                <path d="M5 9a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1h1zm15 0a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1h1zm-13 2a1 1 0 0 1 1 1v0a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v0a1 1 0 0 1 1-1h1zm11 0a1 1 0 0 1 1 1v0a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v0a1 1 0 0 1 1-1h1zM8 11h8v2H8v-2zM2 10a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4zm15 0a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-4z"/>
            </svg>
            <span class="text-xl font-black tracking-wider text-white">MFC</span>
        </a>

        <!-- Desktop Navigation Links (Sesuai Figma: Beranda, Profil, Paket & Harga, Pendaftaran, Kontak) -->
        <nav class="hidden items-center gap-1 lg:gap-1.5 md:flex" aria-label="Navigasi Utama">
            <a href="{{ route('home') }}" 
               class="rounded-xl px-3.5 lg:px-4 py-2 text-xs lg:text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Beranda
            </a>
            <a href="{{ route('profil') }}" 
               class="rounded-xl px-3.5 lg:px-4 py-2 text-xs lg:text-sm font-semibold transition-colors {{ request()->routeIs('profil') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Profil
            </a>
            <a href="{{ route('paket') }}" 
               class="rounded-xl px-3.5 lg:px-4 py-2 text-xs lg:text-sm font-semibold transition-colors {{ request()->routeIs('paket') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Paket & Harga
            </a>
            <a href="{{ route('pendaftaran') }}" 
               class="rounded-xl px-3.5 lg:px-4 py-2 text-xs lg:text-sm font-semibold transition-colors {{ request()->routeIs('pendaftaran') || request()->routeIs('cek-status') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Pendaftaran
            </a>
            <a href="{{ route('kontak') }}" 
               class="rounded-xl px-3.5 lg:px-4 py-2 text-xs lg:text-sm font-semibold transition-colors {{ request()->routeIs('kontak') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Kontak
            </a>
        </nav>

        <!-- Admin link tersembunyi: akses via /admin/login -->

        <!-- Mobile Menu Toggle Button (Touch Friendly 44x44px min) -->
        <div class="flex items-center gap-2 md:hidden">
            <button id="mobile-menu-btn" type="button" aria-expanded="false" aria-controls="mobile-menu" aria-label="Buka Menu Navigasi"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-xl p-2 text-neutral-300 hover:bg-neutral-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-neutral-600 transition-colors">
                <svg id="hamburger-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="close-icon" class="hidden h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobile-menu" class="hidden border-t border-neutral-800 bg-[#1c1d1d] px-4 pt-3 pb-6 md:hidden shadow-xl animate-in slide-in-from-top-2 duration-200">
        <div class="flex flex-col space-y-1">
            <a href="{{ route('home') }}" 
               class="flex items-center min-h-[44px] rounded-xl px-4 py-3 text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Beranda
            </a>
            <a href="{{ route('profil') }}" 
               class="flex items-center min-h-[44px] rounded-xl px-4 py-3 text-sm font-semibold transition-colors {{ request()->routeIs('profil') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Profil
            </a>
            <a href="{{ route('paket') }}" 
               class="flex items-center min-h-[44px] rounded-xl px-4 py-3 text-sm font-semibold transition-colors {{ request()->routeIs('paket') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Paket & Harga
            </a>
            <a href="{{ route('pendaftaran') }}" 
               class="flex items-center min-h-[44px] rounded-xl px-4 py-3 text-sm font-semibold transition-colors {{ request()->routeIs('pendaftaran') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Pendaftaran
            </a>
            <a href="{{ route('cek-status') }}" 
               class="flex items-center min-h-[44px] rounded-xl px-4 py-3 text-sm font-semibold transition-colors {{ request()->routeIs('cek-status') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Cek Status Pendaftaran
            </a>
            <a href="{{ route('kontak') }}" 
               class="flex items-center min-h-[44px] rounded-xl px-4 py-3 text-sm font-semibold transition-colors {{ request()->routeIs('kontak') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Kontak
            </a>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const hamburger = document.getElementById('hamburger-icon');
        const closeIcon = document.getElementById('close-icon');

        if (btn && menu) {
            function toggleMenu(open) {
                const isCurrentlyHidden = menu.classList.contains('hidden');
                const shouldOpen = open !== undefined ? open : isCurrentlyHidden;

                if (shouldOpen) {
                    menu.classList.remove('hidden');
                    hamburger.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                    btn.setAttribute('aria-expanded', 'true');
                } else {
                    menu.classList.add('hidden');
                    hamburger.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    btn.setAttribute('aria-expanded', 'false');
                }
            }

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                toggleMenu();
            });

            // Close on escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !menu.classList.contains('hidden')) {
                    toggleMenu(false);
                }
            });

            // Close when clicking outside header
            document.addEventListener('click', function (e) {
                if (!menu.contains(e.target) && !btn.contains(e.target) && !menu.classList.contains('hidden')) {
                    toggleMenu(false);
                }
            });
        }
    });
</script>
