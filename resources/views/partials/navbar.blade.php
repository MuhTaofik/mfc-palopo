<header class="sticky top-0 z-40 w-full bg-[#1c1d1d] text-white border-b border-neutral-800 transition-all">
    <div class="mx-auto flex h-18 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        
        <!-- Logo Brand (Sesuai Figma: Ikon Dumbbell + MFC) -->
        <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
            <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                <path d="M5 9a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1h1zm15 0a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1h1zm-13 2a1 1 0 0 1 1 1v0a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v0a1 1 0 0 1 1-1h1zm11 0a1 1 0 0 1 1 1v0a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v0a1 1 0 0 1 1-1h1zM8 11h8v2H8v-2zM2 10a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4zm15 0a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-4z"/>
            </svg>
            <span class="text-xl font-black tracking-wider text-white">MFC</span>
        </a>

        <!-- Desktop Navigation Links (Sesuai Figma: Beranda, Profil, Paket & Harga, Pendaftaran, Kontak) -->
        <nav class="hidden items-center gap-1.5 md:flex">
            <a href="{{ route('home') }}" 
               class="rounded-xl px-4 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Beranda
            </a>
            <a href="{{ route('profil') }}" 
               class="rounded-xl px-4 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('profil') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Profil
            </a>
            <a href="{{ route('paket') }}" 
               class="rounded-xl px-4 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('paket') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Paket & Harga
            </a>
            <a href="{{ route('pendaftaran') }}" 
               class="rounded-xl px-4 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('pendaftaran') || request()->routeIs('cek-status') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Pendaftaran
            </a>
            <a href="{{ route('kontak') }}" 
               class="rounded-xl px-4 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('kontak') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60 hover:text-white' }}">
                Kontak
            </a>
        </nav>

        <!-- Right: Admin Login Shortcut -->
        <div class="hidden items-center gap-3 md:flex">
            <a href="{{ route('admin.login') }}" 
               title="Area Admin"
               class="flex items-center gap-2 rounded-xl border border-neutral-700 bg-neutral-900/80 px-3.5 py-1.5 text-xs font-semibold text-neutral-300 hover:border-neutral-500 hover:text-white transition-all">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Admin</span>
            </a>
        </div>

        <!-- Mobile Menu Toggle Button -->
        <div class="flex items-center gap-2 md:hidden">
            <button id="mobile-menu-btn" type="button" aria-expanded="false" 
                    class="inline-flex items-center justify-center rounded-xl p-2 text-neutral-300 hover:bg-neutral-800 hover:text-white focus:outline-none">
                <span class="sr-only">Buka Menu</span>
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
    <div id="mobile-menu" class="hidden border-t border-neutral-800 bg-[#1c1d1d] px-4 pt-3 pb-6 md:hidden">
        <div class="flex flex-col space-y-1.5">
            <a href="{{ route('home') }}" 
               class="rounded-xl px-4 py-2.5 text-sm font-semibold {{ request()->routeIs('home') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60' }}">
                Beranda
            </a>
            <a href="{{ route('profil') }}" 
               class="rounded-xl px-4 py-2.5 text-sm font-semibold {{ request()->routeIs('profil') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60' }}">
                Profil
            </a>
            <a href="{{ route('paket') }}" 
               class="rounded-xl px-4 py-2.5 text-sm font-semibold {{ request()->routeIs('paket') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60' }}">
                Paket & Harga
            </a>
            <a href="{{ route('pendaftaran') }}" 
               class="rounded-xl px-4 py-2.5 text-sm font-semibold {{ request()->routeIs('pendaftaran') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60' }}">
                Pendaftaran
            </a>
            <a href="{{ route('cek-status') }}" 
               class="rounded-xl px-4 py-2.5 text-sm font-semibold {{ request()->routeIs('cek-status') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60' }}">
                Cek Status
            </a>
            <a href="{{ route('kontak') }}" 
               class="rounded-xl px-4 py-2.5 text-sm font-semibold {{ request()->routeIs('kontak') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800/60' }}">
                Kontak
            </a>
            <div class="pt-3 border-t border-neutral-800">
                <a href="{{ route('admin.login') }}" 
                   class="flex w-full items-center justify-center gap-2 rounded-xl bg-neutral-800 py-2.5 text-xs font-semibold text-white hover:bg-neutral-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Area Admin
                </a>
            </div>
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
            btn.addEventListener('click', function () {
                const isHidden = menu.classList.contains('hidden');
                if (isHidden) {
                    menu.classList.remove('hidden');
                    hamburger.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                } else {
                    menu.classList.add('hidden');
                    hamburger.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            });
        }
    });
</script>
