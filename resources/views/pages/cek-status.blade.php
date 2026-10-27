@extends('layouts.main')

@section('title', 'Status Pendaftaran - MULTI FITNES CENTRE (MFC) Palopo')

@section('content')
<div class="bg-[#f5f5f5] py-10 sm:py-14">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Judul (Sesuai Desain Figma Konfirmasi Pendaftaran.png) -->
        <div class="text-center space-y-2">
            <span class="inline-block rounded-full bg-neutral-200/80 px-4 py-1 text-[11px] font-bold uppercase tracking-wider text-neutral-700">
                &bull; MULTI FITNES CENTRE &bull;
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-[#111111]">
                Status Pendaftaran
            </h1>
            <p class="mx-auto max-w-md text-xs sm:text-sm text-neutral-600">
                Pantau verifikasi dan status keanggotaan gym MULTI FITNES CENTRE Anda.
            </p>
        </div>

        <!-- Kolom Pencarian WhatsApp / ID -->
        <div class="rounded-3xl bg-white p-4 sm:p-5 shadow-sm border border-neutral-200/60">
            <form action="{{ route('cek-status') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2.5">
                <div class="relative w-full flex-1">
                    <input type="text" name="kontak" value="{{ $query }}" required
                           placeholder="Ketik nomor WhatsApp Anda (misal: 081234567890)"
                           class="w-full rounded-2xl bg-neutral-100/80 border-0 px-4 py-3 text-xs sm:text-sm text-[#111111] placeholder-neutral-400 focus:bg-white focus:ring-2 focus:ring-black outline-none transition-all">
                </div>
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl bg-[#1c1d1d] px-6 py-3 text-xs font-bold text-white shadow hover:bg-black transition-all">
                    Cek Status
                </button>
            </form>
        </div>

        <!-- Kartu Hasil Status (Sesuai Desain Figma Konfirmasi Pendaftaran.png) -->
        @if($query && $registrations->isNotEmpty())
            @foreach($registrations as $reg)
                <div class="rounded-3xl bg-white p-8 sm:p-10 shadow-sm border border-neutral-200/60 text-center space-y-6">
                    
                    <!-- Icon Lingkaran Hitam Centang Sesuai Figma -->
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#1c1d1d] text-white shadow-md">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <div class="space-y-2">
                        <h2 class="text-xl sm:text-2xl font-black text-[#111111]">
                            @if($reg->status_pendaftaran === 'Diterima')
                                Pendaftaran Telah Diterima!
                            @elseif($reg->status_pendaftaran === 'Ditolak')
                                Pendaftaran Belum Disetujui
                            @else
                                Pendaftaran Berhasil Dikirim
                            @endif
                        </h2>
                        <p class="mx-auto max-w-md text-xs sm:text-sm text-neutral-600 leading-relaxed">
                            @if($reg->status_pendaftaran === 'Diterima')
                                Selamat! Keanggotaan Anda telah aktif. Anda dapat langsung datang ke gym MFC Palopo untuk mulai berlatih.
                            @elseif($reg->status_pendaftaran === 'Ditolak')
                                Mohon maaf, pendaftaran belum dapat diproses. Silakan hubungi pengelola MFC Palopo via WhatsApp.
                            @else
                                Data pendaftaran Anda sudah kami terima. Admin MULTI FITNES CENTRE akan memverifikasi dan menghubungi nomor WhatsApp Anda.
                            @endif
                        </p>
                    </div>

                    <!-- Tabel Ringkasan Abu-abu (Sesuai Figma) -->
                    <div class="rounded-2xl bg-neutral-100/90 p-5 text-left text-xs sm:text-sm space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-neutral-500">Nama Lengkap</span>
                            <span class="font-bold text-[#111111]">{{ $reg->nama }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-neutral-200/60 pt-3">
                            <span class="text-neutral-500">Paket</span>
                            <span class="font-bold text-[#111111]">{{ $reg->package->nama_paket ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-neutral-200/60 pt-3">
                            <span class="text-neutral-500">Jenis Pendaftaran</span>
                            <span class="font-bold text-[#111111]">{{ $reg->jenis_pendaftaran }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-neutral-200/60 pt-3">
                            <span class="text-neutral-500">Tanggal Mulai Latihan</span>
                            <span class="font-bold text-[#111111]">{{ $reg->tanggal_mulai ? $reg->tanggal_mulai->translatedFormat('d M Y') : '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-neutral-200/60 pt-3">
                            <span class="text-neutral-500">Status Pendaftaran</span>
                            <div>
                                @if($reg->status_pendaftaran === 'Diterima')
                                    <span class="inline-flex rounded-full bg-emerald-600 px-3 py-1 text-[11px] font-bold text-white shadow-xs">
                                        Diterima
                                    </span>
                                @elseif($reg->status_pendaftaran === 'Ditolak')
                                    <span class="inline-flex rounded-full bg-rose-600 px-3 py-1 text-[11px] font-bold text-white shadow-xs">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-neutral-200 px-3 py-1 text-[11px] font-bold text-neutral-800">
                                        Menunggu
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Bawah (Sesuai Figma) -->
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                        <a href="{{ route('home') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-[#1c1d1d] px-6 py-3 text-xs font-bold text-white hover:bg-black transition-colors shadow">
                            Kembali ke Beranda
                        </a>
                        <a href="{{ route('paket') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl border border-neutral-300 bg-white px-6 py-3 text-xs font-semibold text-[#1c1d1d] hover:bg-neutral-50 transition-colors">
                            Lihat Paket & Harga
                        </a>
                    </div>

                </div>
            @endforeach
        @elseif($query)
            <div class="rounded-3xl bg-white p-8 sm:p-10 shadow-sm border border-neutral-200/60 text-center space-y-4">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-neutral-100 text-neutral-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-[#111111]">Data Tidak Ditemukan</h3>
                <p class="text-xs text-neutral-500">
                    Tidak ditemukan pendaftaran dengan nomor WhatsApp atau ID <strong>"{{ $query }}"</strong>.
                </p>
                <div class="pt-2">
                    <a href="{{ route('pendaftaran') }}" class="inline-flex rounded-xl bg-[#1c1d1d] px-6 py-2.5 text-xs font-bold text-white hover:bg-black transition-colors">
                        Daftar Baru Sekarang &rarr;
                    </a>
                </div>
            </div>
        @else
            <div class="rounded-3xl bg-white p-8 text-center space-y-3 shadow-sm border border-neutral-200/60">
                <h3 class="text-sm font-bold text-[#111111]">Lacak Pengajuan Pendaftaran Gym Anda</h3>
                <p class="text-xs text-neutral-500 leading-relaxed max-w-sm mx-auto">
                    Masukkan nomor WhatsApp yang Anda daftarkan pada kolom pencarian di atas untuk memeriksa status pengajuan Anda secara *real-time*.
                </p>
            </div>
        @endif

    </div>
</div>
@endsection
