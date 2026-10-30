@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Ringkasan Dashboard')

@section('admin_content')
    <div class="space-y-6 sm:space-y-8">
        
        <!-- Statistik Cards (Grid 4 Kolom) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            
            <!-- Pendaftaran Menunggu -->
            <div class="rounded-2xl sm:rounded-3xl border border-mfc-line bg-white p-5 sm:p-6 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-amber-700">Menunggu Verifikasi</span>
                    <p class="text-2xl sm:text-3xl font-black text-mfc-black mt-1">{{ $stats['pending_registrations'] }}</p>
                    <a href="{{ route('admin.registrations.index', ['status' => 'Menunggu']) }}" class="text-xs text-amber-700 font-semibold hover:underline mt-2 inline-block">
                        Lihat Pendaftaran &rarr;
                    </a>
                </div>
                <div class="h-11 w-11 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 shrink-0">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Member Aktif -->
            <div class="rounded-2xl sm:rounded-3xl border border-mfc-line bg-white p-5 sm:p-6 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-emerald-700">Member Aktif</span>
                    <p class="text-2xl sm:text-3xl font-black text-mfc-black mt-1">{{ $stats['active_members'] }}</p>
                    <a href="{{ route('admin.members.index', ['status' => 'Aktif']) }}" class="text-xs text-emerald-700 font-semibold hover:underline mt-2 inline-block">
                        Kelola Member &rarr;
                    </a>
                </div>
                <div class="h-11 w-11 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 shrink-0">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Member Nonaktif -->
            <div class="rounded-2xl sm:rounded-3xl border border-mfc-line bg-white p-5 sm:p-6 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-mfc-mute">Member Nonaktif</span>
                    <p class="text-2xl sm:text-3xl font-black text-mfc-black mt-1">{{ $stats['inactive_members'] }}</p>
                    <a href="{{ route('admin.members.index', ['status' => 'Nonaktif']) }}" class="text-xs text-mfc-mute font-semibold hover:underline mt-2 inline-block">
                        Lihat Data &rarr;
                    </a>
                </div>
                <div class="h-11 w-11 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl bg-neutral-100 flex items-center justify-center text-neutral-500 shrink-0">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
            </div>

            <!-- Total Pendaftaran -->
            <div class="rounded-2xl sm:rounded-3xl border border-mfc-line bg-white p-5 sm:p-6 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-mfc-mute">Total Registrasi</span>
                    <p class="text-2xl sm:text-3xl font-black text-mfc-black mt-1">{{ $stats['total_registrations'] }}</p>
                    <a href="{{ route('admin.registrations.index') }}" class="text-xs text-mfc-black font-semibold hover:underline mt-2 inline-block">
                        Semua Registrasi &rarr;
                    </a>
                </div>
                <div class="h-11 w-11 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl bg-neutral-100 flex items-center justify-center text-mfc-black shrink-0">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>

        </div>

        <!-- Tabel & Kartu Pendaftaran Terbaru -->
        <div class="rounded-2xl sm:rounded-3xl border border-mfc-line bg-white overflow-hidden shadow-xs">
            <div class="px-5 sm:px-6 py-4 sm:py-5 border-b border-mfc-line flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-mfc-black">Pendaftaran Masuk Terbaru</h2>
                    <p class="text-[11px] sm:text-xs text-mfc-mute">Menampilkan 5 pengajuan pendaftaran gym online terakhir.</p>
                </div>
                <a href="{{ route('admin.registrations.index') }}" class="text-xs font-bold text-mfc-black hover:underline shrink-0">
                    Lihat Semua &rarr;
                </a>
            </div>

            <!-- Mobile View: Card List (< md) -->
            <div class="divide-y divide-mfc-line md:hidden">
                @forelse($recentRegistrations as $reg)
                    <div class="p-4 sm:p-5 space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="font-bold text-sm text-mfc-black">{{ $reg->nama }}</h3>
                                <p class="text-xs text-mfc-mute">{{ $reg->kontak }}</p>
                            </div>
                            <div>
                                @if($reg->status_pendaftaran === 'Menunggu')
                                    <span class="inline-flex rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-[11px] font-semibold text-amber-800">
                                        Menunggu
                                    </span>
                                @elseif($reg->status_pendaftaran === 'Diterima')
                                    <span class="inline-flex rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-800">
                                        Diterima
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-rose-50 border border-rose-200 px-2.5 py-0.5 text-[11px] font-semibold text-rose-800">
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs bg-mfc-page/60 rounded-xl p-3">
                            <div>
                                <span class="text-neutral-400 block text-[10px] uppercase">Paket</span>
                                <span class="font-semibold text-mfc-black">{{ $reg->package->nama_paket ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-neutral-400 block text-[10px] uppercase">Tgl Mulai</span>
                                <span class="font-semibold text-mfc-black">{{ $reg->tanggal_mulai ? $reg->tanggal_mulai->translatedFormat('d M Y') : '-' }}</span>
                            </div>
                            <div>
                                <span class="text-neutral-400 block text-[10px] uppercase">Jenis</span>
                                <span class="font-semibold text-mfc-black">{{ $reg->jenis_pendaftaran }}</span>
                            </div>
                            <div>
                                <span class="text-neutral-400 block text-[10px] uppercase">Diajukan</span>
                                <span class="font-semibold text-mfc-black">{{ $reg->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            @if($reg->status_pendaftaran === 'Menunggu')
                                <form action="{{ route('admin.registrations.accept', $reg) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            onclick="return confirm('Konfirmasi terima pendaftaran {{ $reg->nama }} dan daftarkan sebagai member?')"
                                            class="w-full flex items-center justify-center min-h-[38px] rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition-colors">
                                        Terima
                                    </button>
                                </form>
                                <form action="{{ route('admin.registrations.reject', $reg) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            onclick="return confirm('Tolak pendaftaran {{ $reg->nama }}?')"
                                            class="w-full flex items-center justify-center min-h-[38px] rounded-xl border border-mfc-line px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                                        Tolak
                                    </button>
                                </form>
                            @endif

                            <button type="button" 
                                    onclick="openDeleteModal('{{ route('admin.registrations.destroy', $reg) }}', '{{ addslashes($reg->nama) }}', 'pendaftaran')"
                                    title="Hapus data pendaftaran"
                                    class="{{ $reg->status_pendaftaran === 'Menunggu' ? 'px-3' : 'w-full' }} flex items-center justify-center min-h-[38px] rounded-xl border border-rose-200 bg-rose-50/60 py-2 text-xs font-bold text-rose-600 hover:bg-rose-100 transition-colors gap-1.5">
                                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-mfc-mute">
                        Belum ada data pendaftaran yang masuk.
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Table (>= md) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-mfc-page border-b border-mfc-line text-xs uppercase tracking-wider text-mfc-mute font-bold">
                        <tr>
                            <th class="px-6 py-3.5">Nama & Kontak</th>
                            <th class="px-6 py-3.5">Paket</th>
                            <th class="px-6 py-3.5">Tgl Mulai</th>
                            <th class="px-6 py-3.5">Jenis</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-mfc-line">
                        @forelse($recentRegistrations as $reg)
                            <tr class="hover:bg-mfc-page/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-mfc-black">{{ $reg->nama }}</div>
                                    <div class="text-xs text-mfc-mute">{{ $reg->kontak }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded bg-mfc-field px-2.5 py-1 text-xs font-semibold text-mfc-dark">
                                        {{ $reg->package->nama_paket ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-mfc-dark">
                                    {{ $reg->tanggal_mulai ? $reg->tanggal_mulai->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-xs text-mfc-mute">
                                    {{ $reg->jenis_pendaftaran }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($reg->status_pendaftaran === 'Menunggu')
                                        <span class="inline-flex rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-xs font-semibold text-amber-800">
                                            Menunggu
                                        </span>
                                    @elseif($reg->status_pendaftaran === 'Diterima')
                                        <span class="inline-flex rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                                            Diterima
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-rose-50 border border-rose-200 px-2.5 py-0.5 text-xs font-semibold text-rose-800">
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
                                                        onclick="return confirm('Konfirmasi terima pendaftaran {{ $reg->nama }} dan daftarkan sebagai member?')"
                                                        class="rounded-lg bg-emerald-600 px-2.5 py-1.5 text-xs font-bold text-white hover:bg-emerald-700 transition-colors">
                                                    Terima
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.registrations.reject', $reg) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        title="Tolak Pendaftaran"
                                                        onclick="return confirm('Tolak pendaftaran {{ $reg->nama }}?')"
                                                        class="rounded-lg border border-mfc-line px-2.5 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endif

                                        <button type="button" 
                                                onclick="openDeleteModal('{{ route('admin.registrations.destroy', $reg) }}', '{{ addslashes($reg->nama) }}', 'pendaftaran')"
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
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-mfc-mute">
                                    Belum ada data pendaftaran yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
