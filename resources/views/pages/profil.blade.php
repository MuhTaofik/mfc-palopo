@extends('layouts.main')

@section('title', 'Profil - MULTI FITNES CENTRE (MFC) Palopo')

@section('content')
<div class="bg-[#f5f5f5] py-10 sm:py-14">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Judul -->
        <div class="text-center space-y-2">
            <span class="inline-block rounded-full bg-neutral-200/80 px-4 py-1 text-[11px] font-bold uppercase tracking-wider text-neutral-700">
                &bull; MULTI FITNES CENTRE &bull;
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-[#111111]">
                Tentang
            </h1>
            <p class="mx-auto max-w-2xl text-xs sm:text-sm text-neutral-600 leading-relaxed">
                Mengenal lebih dekat pusat kebugaran untuk membantu Anda menjalani gaya hidup yang lebih sehat dan aktif di Kota Palopo.
            </p>
        </div>

        <!-- Card Utama: Foto & Deskripsi Tempat Berlatih -->
        <div class="rounded-3xl bg-white p-6 sm:p-10 shadow-sm border border-neutral-200/60">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Foto Gym -->
                <div class="lg:col-span-5 overflow-hidden rounded-2xl bg-neutral-900 h-64 sm:h-80 relative shadow-sm">
                    <img src="/images/push.jpg" 
                         alt="Suasana MULTI FITNES CENTRE Palopo" 
                         class="h-full w-full object-cover object-center">
                </div>

                <!-- Konten Tentang Kami -->
                <div class="lg:col-span-7 space-y-4">
                    <span class="inline-block rounded-lg bg-neutral-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-neutral-600">
                        TENTANG KAMI
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-[#111111] leading-tight">
                        Tempat Berlatih untuk Hidup Lebih Bugar
                    </h2>
                    <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed">
                        MULTI FITNES CENTRE merupakan pusat kebugaran yang menyediakan fasilitas latihan untuk membantu pengunjung menjaga kebugaran dan membangun kebiasaan hidup aktif. Dengan fasilitas alat beban dan kardio, pengunjung dapat memilih latihan sesuai kebutuhan masing-masing.
                    </p>

                    <!-- 4 Fitur Pills (2x2 Grid) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div class="flex items-center gap-2.5 rounded-xl bg-neutral-100 px-4 py-3 text-xs font-bold text-[#111111]">
                            <svg class="h-4 w-4 text-neutral-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10v4m3-6v8m3-7v6m6-6v6m3-7v8m3-6v4M2 12h20"/>
                            </svg>
                            <span>Area Free Weights</span>
                        </div>
                        <div class="flex items-center gap-2.5 rounded-xl bg-neutral-100 px-4 py-3 text-xs font-bold text-[#111111]">
                            <svg class="h-4 w-4 text-neutral-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span>Mesin Kardio Lengkap</span>
                        </div>
                        <div class="flex items-center gap-2.5 rounded-xl bg-neutral-100 px-4 py-3 text-xs font-bold text-[#111111]">
                            <svg class="h-4 w-4 text-neutral-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Pelatih Berpengalaman</span>
                        </div>
                        <div class="flex items-center gap-2.5 rounded-xl bg-neutral-100 px-4 py-3 text-xs font-bold text-[#111111]">
                            <svg class="h-4 w-4 text-neutral-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>Lingkungan Higienis</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Visi & Misi (2 Kolom Kartu) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Visi -->
            <div class="rounded-3xl bg-white p-8 shadow-sm border border-neutral-200/60 flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-neutral-100 text-[#111111]">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-[#111111]">Visi</h3>
                    <p class="mt-2 text-xs sm:text-sm text-neutral-600 leading-relaxed">
                        Menjadi pusat kebugaran yang mendukung masyarakat Kota Palopo untuk menjalani gaya hidup sehat dan aktif.
                    </p>
                </div>
            </div>

            <!-- Misi -->
            <div class="rounded-3xl bg-white p-8 shadow-sm border border-neutral-200/60 flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-neutral-100 text-[#111111]">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-[#111111]">Misi</h3>
                    <p class="mt-2 text-xs sm:text-sm text-neutral-600 leading-relaxed">
                        Menyediakan fasilitas latihan yang nyaman, mendukung kebiasaan berolahraga secara konsisten, dan membantu anggota mencapai tujuan kebugaran mereka.
                    </p>
                </div>
            </div>

        </div>

        <!-- Banner Bawah (Mulai Perjalanan Kebugaran Anda) -->
        <div class="relative overflow-hidden rounded-3xl bg-[#111111] p-8 sm:p-10 text-white shadow-md flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="space-y-1 text-center sm:text-left">
                <h3 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                    Mulai Perjalanan Kebugaran Anda
                </h3>
                <p class="text-xs sm:text-sm text-neutral-300">
                    Pilih paket latihan yang sesuai dan bergabung bersama MULTI FITNES CENTRE.
                </p>
            </div>
            <a href="{{ route('paket') }}" 
               class="shrink-0 inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-xs sm:text-sm font-bold text-[#111111] hover:bg-neutral-100 transition-colors shadow">
                Lihat Paket & Harga
            </a>
        </div>

    </div>
</div>
@endsection
