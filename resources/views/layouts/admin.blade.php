<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - MULTI FITNES CENTRE (MFC) Palopo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-mfc-page font-sans text-mfc-ink antialiased flex min-h-screen">

    <!-- Sidebar Gelap (Sesuai Desain Figma Column 5 & 6) -->
    <aside class="w-64 shrink-0 bg-mfc-black text-white flex flex-col justify-between hidden md:flex border-r border-neutral-800">
        <div>
            <!-- Sidebar Header / Logo -->
            <div class="h-20 flex items-center gap-3 px-6 border-b border-neutral-800">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-mfc-black font-black">
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
            <nav class="p-4 space-y-1.5 text-sm">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white text-mfc-black shadow' : 'text-neutral-300 hover:bg-neutral-900 hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.registrations.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-colors {{ request()->routeIs('admin.registrations.*') ? 'bg-white text-mfc-black shadow' : 'text-neutral-300 hover:bg-neutral-900 hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Data Pendaftaran
                </a>

                <a href="{{ route('admin.members.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-colors {{ request()->routeIs('admin.members.*') ? 'bg-white text-mfc-black shadow' : 'text-neutral-300 hover:bg-neutral-900 hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Data Member
                </a>

                <div class="pt-4 mt-4 border-t border-neutral-800">
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-400 hover:bg-neutral-900 hover:text-white transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout Admin
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Top Header Bar -->
        <header class="h-20 border-b border-mfc-line bg-white px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30">
            <div>
                <h1 class="text-lg font-black text-mfc-black">@yield('page_title', 'Dashboard')</h1>
                <p class="text-xs text-mfc-mute">Panel Manajemen MULTI FITNES CENTRE (MFC) Palopo</p>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-xs text-mfc-mute hidden sm:inline-block">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
                <div class="h-9 w-9 rounded-full bg-mfc-black text-white flex items-center justify-center text-xs font-bold uppercase shadow-sm">
                    {{ substr(auth()->guard('admin')->user()->username ?? 'A', 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 sm:px-8 pt-6">
            @if(session('success'))
                <div class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm" role="alert">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="flex items-center justify-between rounded-xl border border-mfc-error/20 bg-mfc-error-bg p-4 text-mfc-error shadow-sm" role="alert">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0 text-mfc-error" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="p-6 sm:p-8 flex-1">
            @yield('admin_content')
        </main>
    </div>

</body>
</html>
