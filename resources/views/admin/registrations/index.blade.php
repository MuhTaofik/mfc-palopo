@extends('layouts.admin')

@section('title', 'Data Pendaftaran')
@section('page_title', 'Kelola Data Pendaftaran')

@section('admin_content')
    <div class="space-y-5 sm:space-y-6">
        
        <!-- Filter Tabs (Sederhana & Konsisten: Hitam, Putih, Oranye) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 -mx-4 px-4 sm:mx-0 sm:px-0 sm:flex-wrap border-b border-mfc-line sm:border-0">
            <a href="{{ route('admin.registrations.index') }}" 
               class="whitespace-nowrap shrink-0 rounded-xl px-3.5 sm:px-4 py-2 min-h-[38px] flex items-center gap-1.5 text-xs font-bold transition-all {{ !$currentStatus ? 'bg-mfc-black text-white shadow-xs border border-mfc-black' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-neutral-50 hover:text-mfc-black' }}">
                @if(!$currentStatus) <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span> @endif
                Semua Pendaftaran
            </a>
            <a href="{{ route('admin.registrations.index', ['status' => 'Menunggu']) }}" 
               class="whitespace-nowrap shrink-0 rounded-xl px-3.5 sm:px-4 py-2 min-h-[38px] flex items-center gap-1.5 text-xs font-bold transition-all {{ $currentStatus === 'Menunggu' ? 'bg-mfc-black text-white shadow-xs border border-mfc-black' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-neutral-50 hover:text-mfc-black' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $currentStatus === 'Menunggu' ? 'bg-orange-500' : 'bg-amber-400' }}"></span>
                Menunggu
            </a>
            <a href="{{ route('admin.registrations.index', ['status' => 'Diterima']) }}" 
               class="whitespace-nowrap shrink-0 rounded-xl px-3.5 sm:px-4 py-2 min-h-[38px] flex items-center gap-1.5 text-xs font-bold transition-all {{ $currentStatus === 'Diterima' ? 'bg-mfc-black text-white shadow-xs border border-mfc-black' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-neutral-50 hover:text-mfc-black' }}">
                @if($currentStatus === 'Diterima') <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span> @endif
                Diterima
            </a>
            <a href="{{ route('admin.registrations.index', ['status' => 'Ditolak']) }}" 
               class="whitespace-nowrap shrink-0 rounded-xl px-3.5 sm:px-4 py-2 min-h-[38px] flex items-center gap-1.5 text-xs font-bold transition-all {{ $currentStatus === 'Ditolak' ? 'bg-mfc-black text-white shadow-xs border border-mfc-black' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-neutral-50 hover:text-mfc-black' }}">
                @if($currentStatus === 'Ditolak') <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span> @endif
                Ditolak
            </a>
        </div>

        <!-- Container Pendaftaran -->
        <div class="rounded-2xl sm:rounded-3xl border border-mfc-line bg-white overflow-hidden shadow-xs">
            
            <!-- Mobile Card View (< md) -->
            <div class="divide-y divide-mfc-line md:hidden">
                @forelse($registrations as $reg)
                    <div class="p-4 sm:p-5 space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-xs text-mfc-dark">#MFC-{{ str_pad($reg->id_registration, 4, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-[10px] text-mfc-mute">{{ $reg->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <h3 class="font-bold text-sm text-mfc-black mt-1">{{ $reg->nama }}</h3>
                            </div>
                            <div>
                                @if($reg->status_pendaftaran === 'Menunggu')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 px-2.5 py-0.5 text-[11px] font-bold text-amber-800">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        Menunggu
                                    </span>
                                @elseif($reg->status_pendaftaran === 'Diterima')
                                    <span class="inline-flex rounded-full bg-[#1c1d1d] px-2.5 py-0.5 text-[11px] font-bold text-white shadow-xs">
                                        Diterima
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-neutral-100 border border-neutral-200 px-2.5 py-0.5 text-[11px] font-semibold text-neutral-600">
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-1.5 text-xs bg-mfc-page/60 rounded-xl p-3">
                            <div class="flex items-center justify-between">
                                <span class="text-neutral-400 text-[10px] uppercase">Kontak</span>
                                <span class="font-semibold text-mfc-black">{{ $reg->kontak }}</span>
                            </div>
                            <div class="flex items-start justify-between gap-2">
                                <span class="text-neutral-400 text-[10px] uppercase shrink-0">Alamat</span>
                                <span class="text-right text-mfc-dark">{{ $reg->alamat }}</span>
                            </div>
                            <div class="flex items-start justify-between border-t border-neutral-200/50 pt-1.5 gap-2">
                                <span class="text-neutral-400 text-[10px] uppercase shrink-0 mt-0.5">Paket & Total</span>
                                <div class="text-right">
                                    <span class="font-bold text-mfc-black">{{ $reg->package->nama_paket ?? '-' }}</span>
                                    <span class="font-bold text-mfc-black"> &bull; Rp {{ number_format($reg->total_biaya, 0, ',', '.') }}</span>
                                    @if($reg->biaya_pendaftaran_awal > 0)
                                        <span class="block text-[10px] text-mfc-mute">(Paket Rp {{ number_format($reg->package->harga ?? 0, 0, ',', '.') }} + Daftar Rp {{ number_format($reg->biaya_pendaftaran_awal, 0, ',', '.') }})</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-neutral-400 text-[10px] uppercase">Rencana Mulai</span>
                                <span class="font-semibold text-mfc-black">{{ $reg->tanggal_mulai ? $reg->tanggal_mulai->translatedFormat('d M Y') : '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-neutral-400 text-[10px] uppercase">Jenis</span>
                                <span class="font-semibold text-mfc-black">{{ $reg->jenis_pendaftaran }}</span>
                            </div>
                            @if($reg->pesan)
                                <div class="border-t border-neutral-200/50 pt-1.5 text-[11px] text-mfc-soft italic">
                                    "{{ $reg->pesan }}"
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            @if($reg->status_pendaftaran === 'Menunggu')
                                <form action="{{ route('admin.registrations.accept', $reg) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            data-confirm="Konfirmasi terima pendaftaran {{ $reg->nama }} dan buat akun member aktif?"
                                            onclick="return confirm(this.dataset.confirm)"
                                            class="w-full flex items-center justify-center min-h-[40px] rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white hover:bg-emerald-700 shadow-xs transition-colors">
                                        Terima
                                    </button>
                                </form>
                                <form action="{{ route('admin.registrations.reject', $reg) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            data-confirm="Tolak pendaftaran {{ $reg->nama }}?"
                                            onclick="return confirm(this.dataset.confirm)"
                                            class="w-full flex items-center justify-center min-h-[40px] rounded-xl border border-mfc-line px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                                        Tolak
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-mfc-mute italic flex-1">
                                    {{ $reg->status_pendaftaran === 'Diterima' ? 'Member Telah Dibuat' : 'Pendaftaran Ditolak' }}
                                </span>
                            @endif

                            <button type="button" 
                                    data-action="{{ route('admin.registrations.destroy', $reg) }}"
                                    data-name="{{ $reg->nama }}"
                                    data-type="pendaftaran"
                                    onclick="openDeleteModal(this.dataset.action, this.dataset.name, this.dataset.type)"
                                    title="Hapus data pendaftaran"
                                    class="{{ $reg->status_pendaftaran === 'Menunggu' ? 'px-3' : 'px-4' }} flex items-center justify-center min-h-[40px] rounded-xl border border-rose-200 bg-rose-50/60 py-2 text-xs font-bold text-rose-600 hover:bg-rose-100 transition-colors gap-1.5 shrink-0">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-mfc-mute">
                        Tidak ada data pendaftaran yang sesuai dengan filter.
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View (>= md) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#1c1d1d] text-white text-xs uppercase tracking-wider font-extrabold border-b border-neutral-800">
                        <tr>
                            <th class="px-6 py-4 text-white">ID & Tgl Daftar</th>
                            <th class="px-6 py-4 text-white">Nama Calon Member</th>
                            <th class="px-6 py-4 text-white">Kontak & Alamat</th>
                            <th class="px-6 py-4 text-white">Paket Dipilih</th>
                            <th class="px-6 py-4 text-white">Rencana Mulai</th>
                            <th class="px-6 py-4 text-white">Status</th>
                            <th class="px-6 py-4 text-right text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-mfc-line">
                        @forelse($registrations as $reg)
                            <tr class="hover:bg-mfc-page/50 transition-colors">
                                <td class="px-6 py-4 text-xs">
                                    <span class="font-bold text-mfc-black block">#MFC-{{ str_pad($reg->id_registration, 4, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-mfc-mute">{{ $reg->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-mfc-black">{{ $reg->nama }}</div>
                                    <span class="inline-block mt-0.5 rounded bg-mfc-field px-2 py-0.5 text-[10px] font-semibold text-mfc-dark">
                                        {{ $reg->jenis_pendaftaran }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-mfc-dark max-w-xs">
                                    <div class="font-semibold text-mfc-black">{{ $reg->kontak }}</div>
                                    <div class="text-mfc-mute truncate" title="{{ $reg->alamat }}">{{ $reg->alamat }}</div>
                                    @if($reg->pesan)
                                        <div class="text-[11px] text-mfc-soft italic mt-0.5">"{{ $reg->pesan }}"</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-lg bg-mfc-field px-2.5 py-1 text-xs font-bold text-mfc-black">
                                        {{ $reg->package->nama_paket ?? '-' }}
                                    </span>
                                    <span class="block text-xs font-bold text-mfc-black mt-1">
                                        Rp {{ number_format($reg->total_biaya, 0, ',', '.') }}
                                    </span>
                                    @if($reg->biaya_pendaftaran_awal > 0)
                                        <span class="block text-[10px] text-mfc-mute mt-0.5">
                                            Termasuk Biaya Daftar Rp {{ number_format($reg->biaya_pendaftaran_awal, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-mfc-dark">
                                    {{ $reg->tanggal_mulai ? $reg->tanggal_mulai->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($reg->status_pendaftaran === 'Menunggu')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 px-3 py-1 text-xs font-bold text-amber-800">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            Menunggu
                                        </span>
                                    @elseif($reg->status_pendaftaran === 'Diterima')
                                        <span class="inline-flex rounded-full bg-[#1c1d1d] px-3 py-1 text-xs font-bold text-white shadow-xs">
                                            Diterima
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-neutral-100 border border-neutral-200 px-3 py-1 text-xs font-semibold text-neutral-600">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($reg->status_pendaftaran === 'Menunggu')
                                            <form action="{{ route('admin.registrations.accept', $reg) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        title="Terima Pendaftaran"
                                                        data-confirm="Konfirmasi terima pendaftaran {{ $reg->nama }} dan buat akun member aktif?"
                                                        onclick="return confirm(this.dataset.confirm)"
                                                        class="rounded-lg bg-emerald-600 px-2.5 py-1.5 text-xs font-bold text-white hover:bg-emerald-700 shadow-xs transition-colors">
                                                    Terima
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.registrations.reject', $reg) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        title="Tolak Pendaftaran"
                                                        data-confirm="Tolak pendaftaran {{ $reg->nama }}?"
                                                        onclick="return confirm(this.dataset.confirm)"
                                                        class="rounded-lg border border-mfc-line px-2.5 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endif

                                        <button type="button" 
                                                data-action="{{ route('admin.registrations.destroy', $reg) }}"
                                                data-name="{{ $reg->nama }}"
                                                data-type="pendaftaran"
                                                onclick="openDeleteModal(this.dataset.action, this.dataset.name, this.dataset.type)"
                                                title="Hapus data pendaftaran"
                                                class="rounded-lg border border-rose-200 bg-rose-50/60 px-2.5 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-100 transition-colors inline-flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span class="hidden sm:inline">Hapus</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-mfc-mute">
                                    Tidak ada data pendaftaran yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($registrations->hasPages())
                <div class="p-4 sm:p-6 border-t border-mfc-line">
                    {{ $registrations->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
