@extends('layouts.main')

@section('title', 'Status Pendaftaran - MULTI FITNES CENTRE (MFC) Palopo')

@section('content')
<div class="bg-[#f5f5f5] py-6 sm:py-10 lg:py-14">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
        
        <!-- Header Judul (Sesuai Desain Figma Konfirmasi Pendaftaran.png) -->
        <div class="text-center space-y-2">
            <span class="inline-block rounded-full bg-neutral-200/80 px-4 py-1 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-neutral-700">
                &bull; MULTI FITNES CENTRE &bull;
            </span>
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight text-[#111111]">
                Status Pendaftaran
            </h1>
            <p class="mx-auto max-w-md text-xs sm:text-sm text-neutral-600">
                Pantau verifikasi dan status keanggotaan gym MULTI FITNES CENTRE Anda.
            </p>
        </div>

        {{-- ── Kartu Kode Pendaftaran (Hanya Muncul Sekali Setelah Mendaftar) ── --}}
        @if(session('kode_pendaftaran'))
            <div class="rounded-2xl sm:rounded-3xl bg-emerald-50 border-2 border-emerald-300 p-5 sm:p-8 text-center space-y-4">
                <div class="mx-auto flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-full bg-emerald-600 text-white shadow-md">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="space-y-1.5">
                    <h2 class="text-lg sm:text-2xl font-black text-emerald-900">{{ session('success') }}</h2>
                    <p class="text-xs sm:text-sm text-emerald-700">
                        Gunakan kode ini bersama nomor WhatsApp Anda untuk mengecek status pendaftaran.
                    </p>
                </div>
                <div class="rounded-xl bg-white border-2 border-dashed border-emerald-300 p-4 sm:p-5">
                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-emerald-600 mb-1.5">Kode Pendaftaran Anda</p>
                    <p class="text-xl sm:text-3xl font-mono font-black tracking-widest text-[#111111] select-all break-all">
                        {{ session('kode_pendaftaran') }}
                    </p>
                </div>
                <div class="rounded-xl bg-amber-50 border border-amber-200 p-3">
                    <p class="text-[11px] sm:text-xs font-semibold text-amber-800">
                        ⚠️ PENTING — Simpan atau screenshot kode ini sekarang! Kode hanya ditampilkan satu kali dan tidak dapat ditampilkan ulang.
                    </p>
                </div>
            </div>
        @endif

        {{-- ── Form Verifikasi: Kode Pendaftaran + Nomor WhatsApp ── --}}
        <div class="rounded-2xl sm:rounded-3xl bg-white p-3.5 sm:p-5 shadow-sm border border-neutral-200/60">
            <form action="{{ route('cek-status.check') }}" method="POST" class="space-y-3">
                @csrf
                <div class="relative w-full">
                    <input type="text" name="kode_pendaftaran" required
                           placeholder="Masukkan kode pendaftaran (MFC-...)" autocomplete="off"
                           class="w-full rounded-xl sm:rounded-2xl bg-neutral-100/80 border-0 px-4 py-3 text-base sm:text-sm text-[#111111] placeholder-neutral-400 focus:bg-white focus:ring-2 focus:ring-black outline-none transition-all min-h-[46px] font-mono tracking-wider uppercase">
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                    <div class="relative w-full flex-1">
                        <input type="text" name="kontak" required inputmode="numeric"
                               placeholder="Nomor WhatsApp (misal: 081234567890)" autocomplete="off"
                               class="w-full rounded-xl sm:rounded-2xl bg-neutral-100/80 border-0 px-4 py-3 text-base sm:text-sm text-[#111111] placeholder-neutral-400 focus:bg-white focus:ring-2 focus:ring-black outline-none transition-all min-h-[46px]">
                    </div>
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center min-h-[46px] rounded-xl sm:rounded-2xl bg-[#1c1d1d] px-6 py-3 text-xs sm:text-sm font-bold text-white shadow hover:bg-black transition-all">
                        Cek Status
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Pesan Kesalahan Generik ── --}}
        @if(isset($error))
            <div class="rounded-2xl sm:rounded-3xl bg-white p-6 sm:p-10 shadow-sm border border-neutral-200/60 text-center space-y-4">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-[#111111]">Verifikasi Gagal</h3>
                <p class="text-xs text-neutral-500">{{ $error }}</p>
            </div>
        @endif

        {{-- ── Validation Errors ── --}}
        @if($errors->any())
            <div class="rounded-2xl sm:rounded-3xl bg-white p-6 sm:p-10 shadow-sm border border-neutral-200/60 text-center space-y-4">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-[#111111]">Data Tidak Lengkap</h3>
                <ul class="text-xs text-neutral-500 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ── Kartu Hasil Status (Desain Figma Dipertahankan) ── --}}
        @if(isset($registration) && $registration)
            <div class="rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-8 lg:p-10 shadow-sm border border-neutral-200/60 text-center space-y-5 sm:space-y-6">
                <!-- Icon Lingkaran Hitam Centang Sesuai Figma -->
                <div class="mx-auto flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-full bg-[#1c1d1d] text-white shadow-md">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <div class="space-y-1.5 sm:space-y-2">
                    <h2 class="text-lg sm:text-2xl font-black text-[#111111]">
                        @if($registration->status_pendaftaran === 'Diterima')
                            Pendaftaran Telah Diterima!
                        @elseif($registration->status_pendaftaran === 'Ditolak')
                            Pendaftaran Belum Disetujui
                        @else
                            Pendaftaran Berhasil Dikirim
                        @endif
                    </h2>
                    <p class="mx-auto max-w-md text-xs sm:text-sm text-neutral-600 leading-relaxed">
                        @if($registration->status_pendaftaran === 'Diterima')
                            Selamat! Keanggotaan Anda telah aktif. Anda dapat langsung datang ke gym MFC Palopo untuk mulai berlatih.
                        @elseif($registration->status_pendaftaran === 'Ditolak')
                            Mohon maaf, pendaftaran belum dapat diproses. Silakan hubungi pengelola MFC Palopo via WhatsApp.
                        @else
                            Data pendaftaran Anda sudah kami terima. Admin MULTI FITNES CENTRE akan memverifikasi dan menghubungi nomor WhatsApp Anda.
                        @endif
                    </p>
                </div>

                <!-- Tabel Ringkasan Abu-abu (Sesuai Figma — Data Privasi Di-mask) -->
                <div class="rounded-xl sm:rounded-2xl bg-neutral-100/90 p-4 sm:p-5 text-left text-xs sm:text-sm space-y-2.5 sm:space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-neutral-500">Nama Pendaftar</span>
                        <span class="font-bold text-[#111111] text-right">{{ $registration->masked_nama }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-t border-neutral-200/60 pt-2.5 sm:pt-3">
                        <span class="text-neutral-500">Paket</span>
                        <span class="font-bold text-[#111111] text-right">{{ $registration->package->nama_paket ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-t border-neutral-200/60 pt-2.5 sm:pt-3">
                        <span class="text-neutral-500">Total Biaya</span>
                        <div class="text-right">
                            <span class="font-bold text-[#111111]">Rp {{ number_format($registration->total_biaya, 0, ',', '.') }}</span>
                            @if($registration->biaya_pendaftaran_awal > 0)
                                <span class="block text-[11px] text-neutral-500 font-normal">(Termasuk Biaya Daftar Rp {{ number_format($registration->biaya_pendaftaran_awal, 0, ',', '.') }})</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-t border-neutral-200/60 pt-2.5 sm:pt-3">
                        <span class="text-neutral-500">Jenis Pendaftaran</span>
                        <span class="font-bold text-[#111111] text-right">{{ $registration->jenis_pendaftaran }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-t border-neutral-200/60 pt-2.5 sm:pt-3">
                        <span class="text-neutral-500">Tanggal Mulai</span>
                        <span class="font-bold text-[#111111] text-right">{{ $registration->tanggal_mulai ? $registration->tanggal_mulai->translatedFormat('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2 border-t border-neutral-200/60 pt-2.5 sm:pt-3">
                        <span class="text-neutral-500">Status</span>
                        <div>
                            @if($registration->status_pendaftaran === 'Diterima')
                                <span class="inline-flex rounded-full bg-emerald-600 px-3 py-1 text-[11px] font-bold text-white shadow-xs">
                                    Diterima
                                </span>
                            @elseif($registration->status_pendaftaran === 'Ditolak')
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
                       class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] rounded-xl bg-[#1c1d1d] px-6 py-3 text-xs font-bold text-white hover:bg-black transition-colors shadow">
                        Kembali ke Beranda
                    </a>
                    <a href="{{ route('paket') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] rounded-xl border border-neutral-300 bg-white px-6 py-3 text-xs font-semibold text-[#1c1d1d] hover:bg-neutral-50 transition-colors">
                        Lihat Paket &amp; Harga
                    </a>
                </div>

            </div>

        {{-- ── State: Belum ada pencarian ── --}}
        @elseif(!isset($error) && !$errors->any() && !session('kode_pendaftaran'))
            <div class="rounded-2xl sm:rounded-3xl bg-white p-6 sm:p-8 text-center space-y-3 shadow-sm border border-neutral-200/60">
                <h3 class="text-sm sm:text-base font-bold text-[#111111]">Lacak Pengajuan Pendaftaran Gym Anda</h3>
                <p class="text-xs text-neutral-500 leading-relaxed max-w-sm mx-auto">
                    Masukkan <strong>kode pendaftaran</strong> dan <strong>nomor WhatsApp</strong> yang Anda daftarkan pada kolom di atas untuk memeriksa status pengajuan Anda.
                </p>
            </div>
        @endif

    </div>
</div>
@endsection
