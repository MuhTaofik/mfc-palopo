@extends('layouts.main')

@section('title', 'Paket & Harga - MULTI FITNES CENTRE (MFC) Palopo')

@section('content')
<div class="bg-[#f5f5f5] py-10 sm:py-14">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Header Judul (Sesuai Desain Figma Paket & Harga.png) -->
        <div class="text-center space-y-2">
            <span class="text-xs font-bold uppercase tracking-widest text-neutral-500">
                MULTI FITNES CENTRE
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-[#111111]">
                Paket & Harga
            </h1>
            <p class="mx-auto max-w-xl text-xs sm:text-sm text-neutral-600">
                Pilih paket latihan sesuai kebutuhan dan waktu Anda.
            </p>
        </div>

        <!-- Dua Kartu Paket (Grid 2 Kolom) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto items-stretch">
            @foreach($packages as $pkg)
                @php
                    $isBulanan = str_contains(strtolower($pkg->nama_paket), 'bulanan');
                @endphp

                <div class="rounded-3xl bg-white overflow-hidden shadow-sm border border-neutral-200/60 flex flex-col justify-between">
                    
                    <!-- Top Collage Banner Foto Gym (Sesuai Figma) -->
                    <div class="h-44 w-full bg-neutral-900 overflow-hidden relative">
                        <img src="{{ $isBulanan ? '/images/dumbell.jpg' : '/images/kardio.jpg' }}" 
                             alt="{{ $pkg->nama_paket }}" 
                             class="h-full w-full object-cover object-center">
                    </div>

                    <!-- Card Body -->
                    <div class="p-8 flex flex-col justify-between flex-1">
                        <div class="space-y-6">
                            
                            <!-- Nama & Harga -->
                            <div>
                                <h2 class="text-xl font-bold text-[#111111]">{{ $pkg->nama_paket }}</h2>
                                <div class="mt-2 text-3xl sm:text-4xl font-black text-[#111111]">
                                    Rp{{ number_format($pkg->harga, 0, ',', '.') }}
                                </div>
                                <p class="text-xs text-neutral-500 mt-1 font-medium">
                                    {{ $isBulanan ? '1 Bulan' : '1 Hari' }}
                                </p>
                                @if($isBulanan)
                                    <p class="text-xs text-neutral-500 mt-0.5 font-medium">
                                        Perpanjangan: Rp{{ number_format($pkg->harga_perpanjangan ?? $pkg->harga, 0, ',', '.') }} / bulan
                                    </p>
                                @endif
                            </div>

                            <!-- Checklist Fitur -->
                            <div class="space-y-3 pt-2 text-sm text-[#111111]">
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
                        <div class="mt-8 pt-4">
                            <a href="{{ route('pendaftaran', ['paket' => $pkg->id_package]) }}" 
                               class="flex w-full items-center justify-center rounded-xl bg-[#1c1d1d] py-3.5 text-center text-sm font-bold text-white shadow-sm hover:bg-black transition-all">
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
