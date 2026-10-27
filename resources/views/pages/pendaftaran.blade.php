@extends('layouts.main')

@section('title', 'Formulir Pendaftaran - MULTI FITNES CENTRE (MFC) Palopo')

@section('content')
<div class="bg-[#f5f5f5] py-10 sm:py-14">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Judul (Sesuai Desain Figma Pendaftaran.png) -->
        <div class="text-center space-y-2">
            <span class="inline-block rounded-full bg-neutral-200/80 px-4 py-1 text-[11px] font-bold uppercase tracking-wider text-neutral-700">
                &bull; MULTI FITNES CENTRE &bull;
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-[#111111]">
                Formulir Pendaftaran
            </h1>
            <p class="mx-auto max-w-md text-xs sm:text-sm text-neutral-600">
                Lengkapi data diri Anda untuk mendaftar menjadi anggota MULTI FITNES CENTRE.
            </p>
        </div>

        <!-- Formulir Card Putih Tengah -->
        <div class="rounded-3xl bg-white p-8 sm:p-10 shadow-sm border border-neutral-200/60">
            <form action="{{ route('pendaftaran.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="nama" class="block text-sm font-bold text-[#111111]">
                            Nama Lengkap
                        </label>
                        <span class="text-xs text-neutral-400 font-medium">Wajib diisi</span>
                    </div>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-neutral-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                               placeholder="Masukkan nama lengkap"
                               class="w-full rounded-2xl bg-neutral-100/80 border-0 pl-11 pr-4 py-3.5 text-sm text-[#111111] placeholder-neutral-400 focus:bg-white focus:ring-2 focus:ring-black outline-none transition-all">
                    </div>
                    @error('nama')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor WhatsApp -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="kontak" class="block text-sm font-bold text-[#111111]">
                            Nomor WhatsApp
                        </label>
                        <span class="text-xs text-neutral-400 font-medium">Untuk verifikasi</span>
                    </div>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-neutral-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <input type="tel" id="kontak" name="kontak" value="{{ old('kontak') }}" required
                               placeholder="Masukkan nomor WhatsApp aktif"
                               class="w-full rounded-2xl bg-neutral-100/80 border-0 pl-11 pr-4 py-3.5 text-sm text-[#111111] placeholder-neutral-400 focus:bg-white focus:ring-2 focus:ring-black outline-none transition-all">
                    </div>
                    @error('kontak')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat Domisili -->
                <div>
                    <label for="alamat" class="block text-sm font-bold text-[#111111] mb-2">
                        Alamat Domisili
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-neutral-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}" required
                               placeholder="Masukkan alamat lengkap tempat tinggal"
                               class="w-full rounded-2xl bg-neutral-100/80 border-0 pl-11 pr-4 py-3.5 text-sm text-[#111111] placeholder-neutral-400 focus:bg-white focus:ring-2 focus:ring-black outline-none transition-all">
                    </div>
                    @error('alamat')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Pendaftaran (Toggle 2 Tombol Sesuai Figma) -->
                <div>
                    <label class="block text-sm font-bold text-[#111111] mb-2">
                        Jenis Pendaftaran
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="jenis-pendaftaran-group">
                        <label class="relative flex items-center justify-center rounded-2xl py-3 px-4 cursor-pointer text-xs font-bold transition-all border border-transparent"
                               :class="selectedJenis === 'Member Baru' ? 'bg-[#111111] text-white shadow' : 'bg-neutral-100 text-[#111111] hover:bg-neutral-200'">
                            <input type="radio" name="jenis_pendaftaran" value="Member Baru" class="sr-only" 
                                   {{ old('jenis_pendaftaran', 'Member Baru') === 'Member Baru' ? 'checked' : '' }}
                                   onchange="updateJenisUI(this.value)">
                            <span id="label-baru">Member Baru &bull; Rp100.000/bulan</span>
                        </label>

                        <label class="relative flex items-center justify-center rounded-2xl py-3 px-4 cursor-pointer text-xs font-bold transition-all border border-transparent"
                               :class="selectedJenis === 'Perpanjangan' ? 'bg-[#111111] text-white shadow' : 'bg-neutral-100 text-[#111111] hover:bg-neutral-200'">
                            <input type="radio" name="jenis_pendaftaran" value="Perpanjangan" class="sr-only"
                                   {{ old('jenis_pendaftaran') === 'Perpanjangan' ? 'checked' : '' }}
                                   onchange="updateJenisUI(this.value)">
                            <span id="label-perpanjangan">Perpanjangan &bull; Rp100.000/bulan</span>
                        </label>
                    </div>
                </div>

                <!-- Pilih Paket Keanggotaan (2 Card Seleksi Sesuai Figma) -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-bold text-[#111111]">
                            Pilih Paket Keanggotaan
                        </label>
                        <span class="text-xs text-neutral-400 font-medium">Transparan & Fleksibel</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($packages as $pkg)
                            @php
                                $isBulanan = str_contains(strtolower($pkg->nama_paket), 'bulanan');
                                $isSelected = (string) old('package_id', $selectedPackageId ?? 2) === (string) $pkg->id_package;
                            @endphp

                            <label class="package-card relative flex flex-col justify-between rounded-2xl p-5 cursor-pointer border-2 transition-all {{ $isSelected ? 'bg-[#111111] text-white border-black shadow' : 'bg-neutral-100/80 text-[#111111] border-transparent hover:bg-neutral-100' }}"
                                   data-id="{{ $pkg->id_package }}"
                                   onclick="selectPackage({{ $pkg->id_package }})">
                                <input type="radio" name="package_id" value="{{ $pkg->id_package }}" class="sr-only" {{ $isSelected ? 'checked' : '' }}>
                                
                                <div>
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-bold text-sm">{{ $pkg->nama_paket }}</h4>
                                            <p class="text-[11px] {{ $isSelected ? 'text-neutral-400' : 'text-neutral-500' }} mt-0.5">
                                                {{ $isBulanan ? 'Akses Penuh 30 Hari' : 'Akses Gym 1 Hari' }}
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-1.5">
                                            @if($isBulanan)
                                                <span class="rounded bg-neutral-800 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-white">
                                                    PALING POPULER
                                                </span>
                                            @endif
                                            <div class="radio-indicator flex h-5 w-5 items-center justify-center rounded-full {{ $isSelected ? 'bg-white text-black' : 'border border-neutral-300' }}">
                                                <svg class="h-3 w-3 check-icon {{ $isSelected ? 'block' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 text-sm font-bold">
                                    Rp {{ number_format($pkg->harga, 0, ',', '.') }} 
                                    <span class="text-[11px] font-normal {{ $isSelected ? 'text-neutral-400' : 'text-neutral-500' }}">/{{ $isBulanan ? 'bulan' : 'hari' }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('package_id')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Mulai Latihan -->
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-bold text-[#111111] mb-2">
                        Tanggal Mulai Latihan
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-neutral-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                               value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required
                               class="w-full rounded-2xl bg-neutral-100/80 border-0 pl-11 pr-4 py-3.5 text-sm text-[#111111] focus:bg-white focus:ring-2 focus:ring-black outline-none transition-all">
                    </div>
                    @error('tanggal_mulai')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full inline-flex items-center justify-center rounded-2xl bg-[#1c1d1d] py-4 px-6 text-sm font-bold text-white shadow-md hover:bg-black transition-all">
                        Kirim Pendaftaran
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    function selectPackage(id) {
        document.querySelectorAll('.package-card').forEach(card => {
            const radio = card.querySelector('input[type="radio"]');
            const indicator = card.querySelector('.radio-indicator');
            const check = card.querySelector('.check-icon');
            
            if (card.dataset.id == id) {
                radio.checked = true;
                card.classList.remove('bg-neutral-100/80', 'text-[#111111]', 'border-transparent');
                card.classList.add('bg-[#111111]', 'text-white', 'border-black', 'shadow');
                indicator.classList.remove('border', 'border-neutral-300');
                indicator.classList.add('bg-white', 'text-black');
                check.classList.remove('hidden');
                check.classList.add('block');
            } else {
                card.classList.remove('bg-[#111111]', 'text-white', 'border-black', 'shadow');
                card.classList.add('bg-neutral-100/80', 'text-[#111111]', 'border-transparent');
                indicator.classList.add('border', 'border-neutral-300');
                indicator.classList.remove('bg-white', 'text-black');
                check.classList.add('hidden');
                check.classList.remove('block');
            }
        });
    }

    function updateJenisUI(val) {
        const labels = document.querySelectorAll('#jenis-pendaftaran-group label');
        labels.forEach(l => {
            const input = l.querySelector('input');
            if (input.value === val) {
                l.classList.remove('bg-neutral-100', 'text-[#111111]');
                l.classList.add('bg-[#111111]', 'text-white', 'shadow');
            } else {
                l.classList.remove('bg-[#111111]', 'text-white', 'shadow');
                l.classList.add('bg-neutral-100', 'text-[#111111]');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkedJenis = document.querySelector('input[name="jenis_pendaftaran"]:checked');
        if (checkedJenis) updateJenisUI(checkedJenis.value);
    });
</script>
@endsection
