@extends('layouts.main')

@section('title', 'Paket & Harga - MULTI FITNES CENTRE (MFC) Palopo')

@section('content')
<div class="bg-[#f5f5f5] py-6 sm:py-10 lg:py-14">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-10">
        
        <!-- Header Judul (Sesuai Desain Figma Paket & Harga.png) -->
        <div class="text-center space-y-2">
            <span class="text-xs font-heading font-bold uppercase tracking-widest text-neutral-500">
                MULTI FITNES CENTRE
            </span>
            <h1 class="text-[34px] sm:text-[40px] lg:text-[46px] font-heading font-bold tracking-tight text-[#111111]">
                Paket &amp; Harga
            </h1>
            <p class="mx-auto max-w-xl text-xs sm:text-sm font-normal text-neutral-600">
                Pilih paket latihan sesuai kebutuhan dan waktu Anda.
            </p>
        </div>

        <!-- Dua Kartu Paket (Grid 2 Kolom) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 max-w-4xl mx-auto items-stretch">
            @foreach($packages as $pkg)
                @php
                    $isBulanan = str_contains(strtolower($pkg->nama_paket), 'bulanan');
                @endphp

                <div class="rounded-2xl sm:rounded-3xl bg-white overflow-hidden shadow-sm border border-neutral-200/60 flex flex-col justify-between">
                    
                    <!-- Top Collage Banner Foto Gym (Sesuai Figma) -->
                    <div class="h-36 sm:h-44 w-full bg-neutral-900 overflow-hidden relative">
                        <img src="{{ $isBulanan ? '/images/dumbell.jpg' : '/images/kardio.jpg' }}" 
                             alt="{{ $pkg->nama_paket }}" 
                             class="h-full w-full object-cover object-center">
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 sm:p-8 flex flex-col justify-between flex-1">
                        <div class="space-y-5 sm:space-y-6">
                            
                            <!-- Nama & Harga -->
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <h2 class="text-xl sm:text-2xl font-heading font-bold text-[#111111] tracking-normal">{{ $pkg->nama_paket }}</h2>
                                    @if($isBulanan)
                                        <span class="rounded-full bg-orange-600 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white shadow-xs">
                                            Populer
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-2 flex items-baseline gap-1.5">
                                    <span class="text-3xl sm:text-4xl lg:text-[44px] font-heading font-extrabold text-[#111111] tracking-tight">
                                        Rp{{ number_format($pkg->harga, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs sm:text-sm font-semibold text-neutral-500">
                                        / {{ $isBulanan ? 'bulan' : 'hari' }}
                                    </span>
                                </div>
                                <p class="text-xs font-normal text-neutral-500 mt-1">
                                    {{ $isBulanan ? 'Akses penuh fasilitas gym selama 30 hari' : 'Akses penuh fasilitas gym 1 hari' }}
                                </p>

                                <!-- Box Rincian Aturan Harga Transparan -->
                                <div class="mt-3.5 rounded-xl bg-neutral-50 border border-neutral-200/80 p-3 space-y-1.5 text-xs">
                                    @if($isBulanan)
                                        <div class="flex items-center justify-between text-neutral-700">
                                            <span class="font-medium">Member Baru:</span>
                                            <span class="font-bold text-[#111111]">Total Rp125.000</span>
                                        </div>
                                        <div class="text-[11px] text-neutral-500 -mt-0.5">
                                            (Paket Rp100.000 + Biaya Pendaftaran Rp25.000)
                                        </div>
                                        <div class="flex items-center justify-between text-neutral-700 border-t border-neutral-200/80 pt-1.5 mt-1">
                                            <span class="font-medium">Perpanjangan:</span>
                                            <span class="font-bold text-[#111111]">Rp100.000 <span class="font-normal text-neutral-500">/ bulan</span></span>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-between text-neutral-700">
                                            <span class="font-medium">Tarif Latihan Harian:</span>
                                            <span class="font-bold text-[#111111]">Rp15.000</span>
                                        </div>
                                        <div class="text-[11px] text-neutral-500">
                                            (Tanpa biaya pendaftaran tambahan)
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Checklist Fitur -->
                            <div class="space-y-2.5 sm:space-y-3 pt-1 sm:pt-2 text-[#111111]">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-black text-white">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-semibold">Akses alat beban</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-black text-white">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-semibold">Akses alat kardio</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-black text-white">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-semibold">Pemakaian fasilitas gym</span>
                                </div>
                            </div>

                        </div>

                        <!-- Tombol Pilih Paket -->
                        <div class="mt-6 sm:mt-8 pt-3 sm:pt-4">
                            <a href="{{ route('pendaftaran', ['paket' => $pkg->id_package]) }}" 
                               class="flex w-full items-center justify-center min-h-[46px] rounded-xl {{ $isBulanan ? 'bg-orange-600 hover:bg-orange-500 text-white shadow-md shadow-orange-600/25' : 'bg-[#1c1d1d] hover:bg-black text-white' }} py-3.5 text-center text-xs sm:text-sm font-semibold transition-all">
                                Pilih Paket
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
