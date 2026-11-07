@extends('layouts.main')

@section('title', 'Beranda - MULTI FITNES CENTRE (MFC) Palopo')

@section('content')
<div class="bg-[#f5f5f5] py-6 sm:py-10 lg:py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- 1. HERO SECTION (Desktop: Side-by-side sesuai Figma / Mobile & Tablet: Unified Hero dengan foto gym & dark overlay) -->

        <!-- Mobile & Tablet Hero (< lg) -->
        <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-neutral-900 text-white shadow-md border border-neutral-800 lg:hidden min-h-[460px] sm:min-h-[500px] flex flex-col justify-end p-6 sm:p-10">
            <!-- Background Image -->
            <img src="/images/hero-beranda.jpeg"
                 alt="Fasilitas MULTI FITNES CENTRE Palopo"
                 class="absolute inset-0 h-full w-full object-cover object-center">

            <!-- Dark Gradient Overlay for Supreme Legibility -->
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/80 to-black/35"></div>

            <!-- Content Over Image -->
            <div class="relative z-10 space-y-4">
                <div class="space-y-2">
                    <span class="inline-block text-[11px] sm:text-xs font-heading font-bold uppercase tracking-widest text-orange-400">
                        MULTI FITNES CENTRE
                    </span>
                    <h1 class="text-[32px] sm:text-[34px] font-heading font-extrabold tracking-tight text-white leading-tight">
                        Latihan Konsisten, Tubuh Lebih Bugar
                    </h1>
                    <p class="text-xs sm:text-sm font-normal text-neutral-200 leading-relaxed pt-1 max-w-xl">
                        Gym umum dengan alat beban &amp; kardio lengkap di Kota Palopo. Fasilitas modern dengan standar latihan optimal untuk setiap tingkatan kebugaran.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2 sm:pt-4">
                    <a href="{{ route('pendaftaran') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center min-h-[46px] rounded-xl bg-orange-600 px-6 py-3.5 text-xs sm:text-sm font-semibold text-white shadow-lg shadow-orange-600/30 hover:bg-orange-500 transition-all">
                        Daftar Sekarang
                        <svg class="ml-2 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('paket') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center min-h-[46px] rounded-xl border border-white/30 bg-black/40 backdrop-blur-xs px-6 py-3.5 text-xs sm:text-sm font-semibold text-white hover:bg-white/10 transition-colors">
                        Lihat Paket &amp; Harga
                    </a>
                </div>
            </div>
        </div>

        <!-- Desktop Hero (>= lg) Sesuai Figma Beranda.png -->
        <div class="hidden lg:grid grid-cols-12 gap-6 items-stretch">
            
            <!-- Left Hero Card -->
            <div class="lg:col-span-6 flex flex-col justify-between rounded-3xl bg-white p-7 lg:p-9 xl:p-12 shadow-sm border border-neutral-200/60 min-h-[420px] lg:min-h-[440px]">
                <div class="space-y-3.5 lg:space-y-4">
                    <span class="text-xs font-heading font-bold uppercase tracking-widest text-neutral-500">
                        MULTI FITNES CENTRE
                    </span>
                    <h1 class="text-3xl lg:text-[40px] xl:text-[42px] font-heading font-extrabold tracking-tight text-[#111111] leading-tight">
                        Latihan Konsisten, Tubuh Lebih Bugar
                    </h1>
                    <p class="text-xs lg:text-sm xl:text-base font-normal text-neutral-600 leading-relaxed pt-1">
                        Gym umum dengan alat beban &amp; kardio lengkap di Kota Palopo. Fasilitas modern dengan standar latihan optimal untuk setiap tingkatan kebugaran.
                    </p>
                </div>

                <div class="flex items-center gap-3 pt-6 lg:pt-8">
                    <a href="{{ route('pendaftaran') }}" 
                       class="inline-flex items-center justify-center min-h-[46px] rounded-xl bg-orange-600 px-6 py-3.5 text-xs lg:text-sm font-semibold text-white shadow-md shadow-orange-600/20 hover:bg-orange-500 transition-all">
                        Daftar Sekarang
                        <svg class="ml-2 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('paket') }}" 
                       class="inline-flex items-center justify-center min-h-[46px] rounded-xl border border-neutral-300 bg-white px-6 py-3.5 text-xs lg:text-sm font-semibold text-[#1c1d1d] hover:bg-neutral-50 transition-colors">
                        Lihat Paket &amp; Harga
                    </a>
                </div>
            </div>

            <!-- Right Hero Image (Foto Gym MFC Palopo) -->
            <div class="lg:col-span-6 overflow-hidden rounded-3xl bg-neutral-900 shadow-sm border border-neutral-200/60 relative min-h-[420px] lg:min-h-[440px]">
                <img src="/images/hero-beranda.jpeg" 
                     alt="Fasilitas MULTI FITNES CENTRE Palopo" 
                     class="absolute inset-0 h-full w-full object-cover object-center">
            </div>

        </div>

        <!-- 2. TIGA KARTU FASILITAS UTAMA (Alat Beban, Alat Kardio, Alat Pull & Push) -->
        <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-3">
            
            <!-- Card 1: Alat Beban -->
            <a href="{{ route('profil') }}" class="group rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-sm border border-neutral-200/60 transition-all hover:-translate-y-1 hover:shadow-md flex flex-col justify-between">
                <div>
                    <div class="h-40 sm:h-44 w-full overflow-hidden rounded-xl sm:rounded-2xl bg-neutral-800">
                        <img src="/images/dumbell.jpg" 
                             alt="Alat Beban" 
                             class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="mt-4 sm:mt-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg sm:text-xl font-heading font-bold text-[#111111] tracking-normal group-hover:text-black">Alat Beban</h3>
                            <p class="text-xs text-neutral-500 mt-0.5 sm:mt-1">Dumbbell & Barbell lengkap</p>
                        </div>
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-neutral-400 group-hover:text-black group-hover:translate-x-1 transition-all shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Card 2: Alat Kardio -->
            <a href="{{ route('profil') }}" class="group rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-sm border border-neutral-200/60 transition-all hover:-translate-y-1 hover:shadow-md flex flex-col justify-between">
                <div>
                    <div class="h-40 sm:h-44 w-full overflow-hidden rounded-xl sm:rounded-2xl bg-neutral-800">
                        <img src="/images/kardio.jpg" 
                             alt="Alat Kardio" 
                             class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="mt-4 sm:mt-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg sm:text-xl font-heading font-bold text-[#111111] tracking-normal group-hover:text-black">Alat Kardio</h3>
                            <p class="text-xs text-neutral-500 mt-0.5 sm:mt-1">Sepeda statis & Leg Press Machine</p>
                        </div>
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-neutral-400 group-hover:text-black group-hover:translate-x-1 transition-all shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Card 3: Alat Pull & Push -->
            <a href="{{ route('profil') }}" class="group rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-sm border border-neutral-200/60 transition-all hover:-translate-y-1 hover:shadow-md flex flex-col justify-between">
                <div>
                    <div class="h-40 sm:h-44 w-full overflow-hidden rounded-xl sm:rounded-2xl bg-neutral-800">
                        <img src="/images/push.jpg" 
                             alt="Alat Pull & Push" 
                             class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="mt-4 sm:mt-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg sm:text-xl font-heading font-bold text-[#111111] tracking-normal group-hover:text-black">Alat Pull & Push</h3>
                            <p class="text-xs text-neutral-500 mt-0.5 sm:mt-1">Lat Pulldown & Chest Press Machine</p>
                        </div>
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-neutral-400 group-hover:text-black group-hover:translate-x-1 transition-all shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

        </div>

        <!-- 3. STATISTIK LOKASI & ALAT (White Card Sesuai Desain Figma) -->
        <div class="rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-8 lg:p-10 shadow-sm border border-neutral-200/60">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 text-center">
                
                <div class="space-y-0.5 sm:space-y-1">
                    <p class="text-3xl sm:text-4xl lg:text-[42px] font-heading font-extrabold text-[#111111] tracking-tight">13+</p>
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-neutral-500">Unit Alat Beban</p>
                </div>

                <div class="space-y-0.5 sm:space-y-1">
                    <p class="text-3xl sm:text-4xl lg:text-[42px] font-heading font-extrabold text-[#111111] tracking-tight">5+</p>
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-neutral-500">Station Kardio</p>
                </div>

                <div class="space-y-0.5 sm:space-y-1">
                    <p class="text-3xl sm:text-4xl lg:text-[42px] font-heading font-extrabold text-[#111111] tracking-tight">5+</p>
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-neutral-500">Unit Alat Pull & Push</p>
                </div>

                <div class="space-y-0.5 sm:space-y-1">
                    <p class="text-3xl sm:text-4xl lg:text-[42px] font-heading font-extrabold text-[#111111] tracking-tight">Palopo</p>
                    <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-neutral-500">Lokasi Strategis</p>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
