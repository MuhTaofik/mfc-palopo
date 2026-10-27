@extends('layouts.main')

@section('title', 'Beranda - MULTI FITNES CENTRE (MFC) Palopo')

@section('content')
<div class="bg-[#f5f5f5] py-8 sm:py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- 1. HERO SECTION (Sesuai Desain Figma Beranda.png) -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 items-stretch">
            
            <!-- Left Hero Card -->
            <div class="lg:col-span-6 flex flex-col justify-between rounded-3xl bg-white p-8 sm:p-12 shadow-sm border border-neutral-200/60">
                <div class="space-y-4">
                    <span class="text-xs font-bold uppercase tracking-widest text-neutral-500">
                        MULTI FITNES CENTRE
                    </span>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-[#111111] leading-tight">
                        Latihan Konsisten, Tubuh Lebih Bugar
                    </h1>
                    <p class="text-sm sm:text-base text-neutral-600 leading-relaxed pt-1">
                        Gym umum dengan alat beban & kardio lengkap di Kota Palopo. Fasilitas modern dengan standar latihan optimal untuk setiap tingkatan kebugaran.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3.5 pt-8">
                    <a href="{{ route('pendaftaran') }}" 
                       class="inline-flex items-center justify-center rounded-xl bg-[#1c1d1d] px-6 py-3.5 text-sm font-bold text-white shadow hover:bg-black transition-all">
                        Daftar Sekarang
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('paket') }}" 
                       class="inline-flex items-center justify-center rounded-xl border border-neutral-300 bg-white px-6 py-3.5 text-sm font-semibold text-[#1c1d1d] hover:bg-neutral-50 transition-colors">
                        Lihat Paket & Harga
                    </a>
                </div>
            </div>

            <!-- Right Hero Image (Foto Gym MFC Palopo) -->
            <div class="lg:col-span-6 overflow-hidden rounded-3xl bg-neutral-900 shadow-sm border border-neutral-200/60 min-h-[340px] relative">
                <img src="/images/hero-beranda.jpeg" 
                     alt="Fasilitas MULTI FITNES CENTRE Palopo" 
                     class="h-full w-full object-cover object-center">
            </div>

        </div>

        <!-- 2. TIGA KARTU FASILITAS UTAMA (Alat Beban, Alat Kardio, Alat Pull & Push) -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            
            <!-- Card 1: Alat Beban -->
            <a href="{{ route('profil') }}" class="group rounded-3xl bg-white p-6 shadow-sm border border-neutral-200/60 transition-all hover:-translate-y-1 hover:shadow-md flex flex-col justify-between">
                <div>
                    <div class="h-44 w-full overflow-hidden rounded-2xl bg-neutral-800">
                        <img src="/images/dumbell.jpg" 
                             alt="Alat Beban" 
                             class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="mt-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-[#111111] group-hover:text-black">Alat Beban</h3>
                            <p class="text-xs text-neutral-500 mt-1">Dumbbell & Barbell lengkap</p>
                        </div>
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-neutral-400 group-hover:text-black group-hover:translate-x-1 transition-all">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Card 2: Alat Kardio -->
            <a href="{{ route('profil') }}" class="group rounded-3xl bg-white p-6 shadow-sm border border-neutral-200/60 transition-all hover:-translate-y-1 hover:shadow-md flex flex-col justify-between">
                <div>
                    <div class="h-44 w-full overflow-hidden rounded-2xl bg-neutral-800">
                        <img src="/images/kardio.jpg" 
                             alt="Alat Kardio" 
                             class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="mt-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-[#111111] group-hover:text-black">Alat Kardio</h3>
                            <p class="text-xs text-neutral-500 mt-1">Sepeda statis & Leg Press Machine</p>
                        </div>
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-neutral-400 group-hover:text-black group-hover:translate-x-1 transition-all">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Card 3: Alat Pull & Push -->
            <a href="{{ route('profil') }}" class="group rounded-3xl bg-white p-6 shadow-sm border border-neutral-200/60 transition-all hover:-translate-y-1 hover:shadow-md flex flex-col justify-between">
                <div>
                    <div class="h-44 w-full overflow-hidden rounded-2xl bg-neutral-800">
                        <img src="/images/push.jpg" 
                             alt="Alat Pull & Push" 
                             class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="mt-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-[#111111] group-hover:text-black">Alat Pull & Push</h3>
                            <p class="text-xs text-neutral-500 mt-1">Lat Pulldown Machine & Chest Press Machine</p>
                        </div>
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-neutral-400 group-hover:text-black group-hover:translate-x-1 transition-all">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

        </div>

        <!-- 3. STATISTIK LOKASI & ALAT (White Card Sesuai Desain Figma) -->
        <div class="rounded-3xl bg-white p-8 sm:p-10 shadow-sm border border-neutral-200/60">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
                
                <div class="space-y-1">
                    <p class="text-3xl sm:text-4xl font-black text-[#111111]">13+</p>
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-neutral-500">Unit Alat Beban</p>
                </div>

                <div class="space-y-1">
                    <p class="text-3xl sm:text-4xl font-black text-[#111111]">5+</p>
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-neutral-500">Station Kardio</p>
                </div>

                <div class="space-y-1">
                    <p class="text-3xl sm:text-4xl font-black text-[#111111]">5+</p>
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-neutral-500">Unit Alat Pull & Push</p>
                </div>

                <div class="space-y-1">
                    <p class="text-3xl sm:text-4xl font-black text-[#111111]">Palopo</p>
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-neutral-500">Lokasi Strategis</p>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
