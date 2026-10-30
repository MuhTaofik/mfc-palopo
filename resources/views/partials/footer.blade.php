<footer class="bg-[#1c1d1d] text-white py-6 border-t border-neutral-800">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 text-center sm:flex-row sm:text-left sm:px-6 lg:px-8">
        
        <!-- Left: Logo & Nama -->
        <div class="flex items-center gap-2.5">
            <svg class="h-5 w-5 text-white shrink-0" viewBox="0 0 24 24" fill="currentColor">
                <path d="M5 9a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1h1zm15 0a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1h1zm-13 2a1 1 0 0 1 1 1v0a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v0a1 1 0 0 1 1-1h1zm11 0a1 1 0 0 1 1 1v0a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v0a1 1 0 0 1 1-1h1zM8 11h8v2H8v-2zM2 10a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-4zm15 0a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-4z"/>
            </svg>
            <span class="text-xs font-black tracking-widest text-white uppercase">MULTI FITNES CENTRE</span>
        </div>

        <!-- Center: Copyright -->
        <div class="text-xs text-neutral-400">
            &copy; {{ date('Y') }} MULTI FITNES CENTRE. Hak Cipta Dilindungi.
        </div>

        <!-- Right: Links -->
        <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6 text-xs text-neutral-400">
            <a href="{{ route('profil') }}" class="hover:text-white transition-colors py-1">Kebijakan Privasi</a>
            <a href="{{ route('kontak') }}" class="hover:text-white transition-colors py-1">Bantuan</a>
            <a href="{{ route('admin.login') }}" class="hover:text-white transition-colors py-1">Admin</a>
        </div>

    </div>
</footer>
