@extends('layouts.main')

@section('title', 'Kontak Kami - MULTI FITNES CENTRE (MFC) Palopo')

@section('content')
<div class="bg-[#f5f5f5] py-6 sm:py-10 lg:py-14">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
        
        <!-- Header Judul (Sesuai Desain Figma Kontak.png) -->
        <div class="text-center space-y-2">
            <span class="inline-block rounded-full bg-neutral-200/80 px-4 py-1 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-neutral-700">
                &bull; MULTI FITNES CENTRE &bull;
            </span>
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight text-[#111111]">
                Kontak Kami
            </h1>
            <p class="mx-auto max-w-lg text-xs sm:text-sm text-neutral-600 leading-relaxed">
                Hubungi kami jika Anda memiliki pertanyaan, membutuhkan informasi, atau ingin mendaftar menjadi anggota.
            </p>
        </div>

        <!-- Dua Kolom: 4 Kartu Informasi di Kiri & Peta Google Maps di Kanan -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
            
            <!-- Kolom Kiri: 4 Kartu Informasi Kontak -->
            <div class="lg:col-span-5 space-y-3.5 sm:space-y-4">
                
                <!-- Card 1: WhatsApp -->
                <div class="rounded-2xl sm:rounded-3xl bg-white p-4 sm:p-6 shadow-sm border border-neutral-200/60 flex items-start gap-3.5 sm:gap-4">
                    <div class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-xl sm:rounded-2xl bg-neutral-100 text-[#111111]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div class="space-y-2.5 sm:space-y-3 min-w-0 flex-1">
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-[#111111]">WhatsApp</h3>
                            <p class="text-xs text-neutral-600 mt-0.5 break-all sm:break-normal">Hubungi kami di +62 858-5690-4817</p>
                        </div>
                        <a href="https://wa.me/6285856904817" target="_blank" 
                           class="inline-flex items-center justify-center min-h-[38px] rounded-xl bg-[#1c1d1d] px-4 sm:px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-black transition-all">
                            Chat Sekarang
                        </a>
                    </div>
                </div>

                <!-- Card 2: Alamat -->
                <div class="rounded-2xl sm:rounded-3xl bg-white p-4 sm:p-6 shadow-sm border border-neutral-200/60 flex items-start gap-3.5 sm:gap-4">
                    <div class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-xl sm:rounded-2xl bg-neutral-100 text-[#111111]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-sm sm:text-base font-bold text-[#111111]">Alamat</h3>
                        <p class="text-xs text-neutral-600 mt-1 leading-relaxed">
                            X6V2+HWJ, Surutanga, Kec. Wara, Kota Palopo, Sulawesi Selatan 91911
                        </p>
                    </div>
                </div>

                <!-- Card 3: Jam Operasional -->
                <div class="rounded-2xl sm:rounded-3xl bg-white p-4 sm:p-6 shadow-sm border border-neutral-200/60 flex items-start gap-3.5 sm:gap-4">
                    <div class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-xl sm:rounded-2xl bg-neutral-100 text-[#111111]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-sm sm:text-base font-bold text-[#111111]">Jam Operasional</h3>
                        <p class="text-xs text-neutral-600 mt-1 leading-relaxed">
                            Senin &ndash; Sabtu : 08.00 &ndash; 21.00 WITA<br>
                            Minggu : 08.00 &ndash; 18.00 WITA
                        </p>
                    </div>
                </div>

                <!-- Card 4: Email -->
                <div class="rounded-2xl sm:rounded-3xl bg-white p-4 sm:p-6 shadow-sm border border-neutral-200/60 flex items-start gap-3.5 sm:gap-4">
                    <div class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-xl sm:rounded-2xl bg-neutral-100 text-[#111111]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-sm sm:text-base font-bold text-[#111111]">Email</h3>
                        <p class="text-xs text-neutral-600 mt-1 break-all">aryadialias717@gmail.com</p>
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Peta Google Maps Interaktif -->
            <div class="lg:col-span-7 rounded-2xl sm:rounded-3xl bg-white overflow-hidden shadow-sm border border-neutral-200/60 relative h-[320px] sm:h-[420px] lg:h-[470px]">
                
                <!-- Overlay Card Info Lokasi -->
                <div class="absolute top-3 left-3 sm:top-6 sm:left-6 z-10 rounded-xl sm:rounded-2xl bg-white/95 backdrop-blur-sm p-3 sm:p-4 shadow-md border border-neutral-200/80 max-w-[calc(100%-1.5rem)] sm:max-w-xs space-y-1">
                    <div class="flex items-center gap-1.5 text-xs font-bold text-[#111111]">
                        <span class="h-2 w-2 rounded-full bg-red-600 shrink-0"></span>
                        <span class="truncate">MULTI FITNES CENTRE</span>
                    </div>
                    <p class="text-[10px] sm:text-[11px] text-neutral-600 leading-tight">Surutanga, Wara, Kota Palopo, Sulsel.</p>
                    <a href="https://maps.app.goo.gl/XwWRP491b8SXJnXG7" target="_blank" 
                       class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] font-bold text-black hover:underline pt-0.5">
                        Lihat di Google Maps &rarr;
                    </a>
                </div>

                <!-- Google Maps Embed (Koordinat Persis Lokasi MFC Palopo) -->
                <iframe 
                    title="Peta Lokasi MULTI FITNES CENTRE Kota Palopo"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.3284127774336!2d120.1996835!3d-3.0060306!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d915f1e8f0f6edd%3A0x9fd6cc251875a46b!2sMulti%20Fitnes!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    class="h-full w-full">
                </iframe>
            </div>

        </div>

        <!-- Banner Bawah: Bergabung Bersama MULTI FITNES CENTRE Sekarang! -->
        <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-[#111111] p-6 sm:p-10 text-white shadow-md flex flex-col sm:flex-row items-center justify-between gap-5 sm:gap-6 text-center sm:text-left">
            <div class="space-y-1">
                <h3 class="text-lg sm:text-2xl font-black tracking-tight text-white">
                    Bergabung Bersama MULTI FITNES CENTRE Sekarang!
                </h3>
                <p class="text-xs sm:text-sm text-neutral-300">
                    Wujudkan tubuh sehat dan bugar dengan fasilitas terbaik di Kota Palopo.
                </p>
            </div>
            <a href="{{ route('pendaftaran') }}" 
               class="w-full sm:w-auto shrink-0 inline-flex items-center justify-center min-h-[44px] rounded-xl bg-white px-6 py-3 text-xs sm:text-sm font-bold text-[#111111] hover:bg-neutral-100 transition-colors shadow">
                Daftar Sekarang
            </a>
        </div>

    </div>
</div>
@endsection
