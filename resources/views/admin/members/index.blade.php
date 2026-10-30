@extends('layouts.admin')

@section('title', 'Data Member')
@section('page_title', 'Kelola Data Member Gym')

@section('admin_content')
    <div class="space-y-5 sm:space-y-6">
        
        <!-- Filter & Search Bar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 sm:gap-4">
            
            <!-- Filter Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0 -mx-4 px-4 sm:mx-0 sm:px-0 sm:flex-wrap">
                <a href="{{ route('admin.members.index') }}" 
                   class="whitespace-nowrap shrink-0 rounded-xl px-3.5 sm:px-4 py-2 min-h-[38px] flex items-center text-xs font-bold transition-colors {{ !$currentStatus ? 'bg-mfc-black text-white shadow-xs' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-mfc-page' }}">
                    Semua Member
                </a>
                <a href="{{ route('admin.members.index', ['status' => 'Aktif']) }}" 
                   class="whitespace-nowrap shrink-0 rounded-xl px-3.5 sm:px-4 py-2 min-h-[38px] flex items-center text-xs font-bold transition-colors {{ $currentStatus === 'Aktif' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-mfc-page' }}">
                    Member Aktif
                </a>
                <a href="{{ route('admin.members.index', ['status' => 'Nonaktif']) }}" 
                   class="whitespace-nowrap shrink-0 rounded-xl px-3.5 sm:px-4 py-2 min-h-[38px] flex items-center text-xs font-bold transition-colors {{ $currentStatus === 'Nonaktif' ? 'bg-neutral-800 text-white shadow-xs' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-mfc-page' }}">
                    Nonaktif
                </a>
            </div>

            <!-- Search Box -->
            <form action="{{ route('admin.members.index') }}" method="GET" class="flex items-center gap-2 max-w-sm w-full">
                @if($currentStatus)
                    <input type="hidden" name="status" value="{{ $currentStatus }}">
                @endif
                <div class="relative w-full">
                    <input type="text" name="q" value="{{ $search }}" 
                           placeholder="Cari nama atau kontak..."
                           class="w-full rounded-xl border border-mfc-line bg-white pl-4 pr-10 py-2.5 text-xs text-mfc-ink outline-none focus:border-mfc-black min-h-[40px]">
                    @if($search)
                        <a href="{{ route('admin.members.index', ['status' => $currentStatus]) }}" class="absolute right-3 top-2.5 text-mfc-mute hover:text-mfc-black text-xs font-bold">
                            &times;
                        </a>
                    @endif
                </div>
                <button type="submit" class="rounded-xl bg-mfc-black px-4 py-2.5 min-h-[40px] text-xs font-bold text-white hover:bg-mfc-dark shrink-0">
                    Cari
                </button>
            </form>

        </div>

        <!-- Container Member -->
        <div class="rounded-2xl sm:rounded-3xl border border-mfc-line bg-white overflow-hidden shadow-xs">
            
            <!-- Mobile Card View (< md) -->
            <div class="divide-y divide-mfc-line md:hidden">
                @forelse($members as $mbr)
                    @php
                        $isExpired = $mbr->tanggal_berakhir && $mbr->tanggal_berakhir->isPast();
                    @endphp
                    <div class="p-4 sm:p-5 space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-xs font-bold text-mfc-dark block">#MBR-{{ str_pad($mbr->id_member, 4, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="font-bold text-sm text-mfc-black mt-0.5">{{ $mbr->nama }}</h3>
                                <p class="text-xs text-mfc-mute">{{ $mbr->kontak }}</p>
                            </div>
                            <div>
                                @if($mbr->status === 'Aktif' && !$isExpired)
                                    <span class="inline-flex rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[11px] font-bold text-emerald-800">
                                        Aktif
                                    </span>
                                @elseif($mbr->status === 'Aktif' && $isExpired)
                                    <span class="inline-flex rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-[11px] font-bold text-amber-800">
                                        Kedaluwarsa
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-neutral-100 border border-neutral-300 px-2.5 py-0.5 text-[11px] font-bold text-neutral-600">
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs bg-mfc-page/60 rounded-xl p-3">
                            <div>
                                <span class="text-neutral-400 block text-[10px] uppercase">Paket</span>
                                <span class="font-semibold text-mfc-black">{{ $mbr->package->nama_paket ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-neutral-400 block text-[10px] uppercase">Tgl Daftar</span>
                                <span class="font-semibold text-mfc-black">{{ $mbr->tanggal_daftar ? $mbr->tanggal_daftar->translatedFormat('d M Y') : '-' }}</span>
                            </div>
                            <div class="col-span-2 pt-1 border-t border-neutral-200/50 flex items-center justify-between">
                                <div>
                                    <span class="text-neutral-400 block text-[10px] uppercase">Berakhir Pada</span>
                                    <span class="font-bold {{ $isExpired ? 'text-rose-600' : 'text-mfc-black' }}">
                                        {{ $mbr->tanggal_berakhir ? $mbr->tanggal_berakhir->translatedFormat('d M Y') : '-' }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    @if($isExpired)
                                        <span class="text-[10px] text-rose-500 font-semibold block">Sudah Berakhir</span>
                                    @else
                                        <span class="text-[10px] text-emerald-600 font-semibold block">
                                            Sisa {{ now()->diffInDays($mbr->tanggal_berakhir, false) + 1 }} hari
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 pt-1">
                            <form action="{{ route('admin.members.extend', $mbr) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        onclick="return confirm('Perpanjang masa aktif membership {{ $mbr->nama }}?')"
                                        class="w-full flex items-center justify-center min-h-[40px] rounded-xl bg-mfc-black px-3 py-2 text-xs font-bold text-white hover:bg-mfc-dark shadow-xs transition-colors">
                                    Perpanjang
                                </button>
                            </form>

                            <form action="{{ route('admin.members.toggle-status', $mbr) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full flex items-center justify-center min-h-[40px] rounded-xl border border-mfc-line px-3 py-2 text-xs font-semibold text-mfc-mute hover:bg-mfc-field transition-colors">
                                    {{ $mbr->status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <button type="button" 
                                    onclick="openDeleteModal('{{ route('admin.members.destroy', $mbr) }}', '{{ addslashes($mbr->nama) }}', 'member')"
                                    title="Hapus data member"
                                    class="flex items-center justify-center min-h-[40px] rounded-xl border border-rose-200 bg-rose-50/60 px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-100 transition-colors shrink-0">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-mfc-mute">
                        Tidak ada data member yang ditemukan.
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View (>= md) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-mfc-page border-b border-mfc-line text-xs uppercase tracking-wider text-mfc-mute font-bold">
                        <tr>
                            <th class="px-6 py-4">ID Member</th>
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">Kontak</th>
                            <th class="px-6 py-4">Paket</th>
                            <th class="px-6 py-4">Tgl Daftar</th>
                            <th class="px-6 py-4">Berakhir Pada</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-mfc-line">
                        @forelse($members as $mbr)
                            @php
                                $isExpired = $mbr->tanggal_berakhir && $mbr->tanggal_berakhir->isPast();
                            @endphp
                            <tr class="hover:bg-mfc-page/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-mfc-dark">
                                    #MBR-{{ str_pad($mbr->id_member, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-mfc-black">{{ $mbr->nama }}</div>
                                    @if($mbr->registration_id)
                                        <span class="text-[10px] text-mfc-mute">Reg: #MFC-{{ str_pad($mbr->registration_id, 4, '0', STR_PAD_LEFT) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-mfc-dark font-medium">
                                    {{ $mbr->kontak }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded bg-mfc-field px-2.5 py-1 text-xs font-semibold text-mfc-dark">
                                        {{ $mbr->package->nama_paket ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-mfc-dark">
                                    {{ $mbr->tanggal_daftar ? $mbr->tanggal_daftar->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <div class="font-bold {{ $isExpired ? 'text-rose-600' : 'text-mfc-black' }}">
                                        {{ $mbr->tanggal_berakhir ? $mbr->tanggal_berakhir->translatedFormat('d M Y') : '-' }}
                                    </div>
                                    @if($isExpired)
                                        <span class="text-[10px] text-rose-500 font-semibold block">Sudah Berakhir</span>
                                    @else
                                        <span class="text-[10px] text-emerald-600 font-semibold block">
                                            Sisa {{ now()->diffInDays($mbr->tanggal_berakhir, false) + 1 }} hari
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($mbr->status === 'Aktif' && !$isExpired)
                                        <span class="inline-flex rounded-full bg-emerald-50 border border-emerald-200 px-3 py-0.5 text-xs font-bold text-emerald-800">
                                            Aktif
                                        </span>
                                    @elseif($mbr->status === 'Aktif' && $isExpired)
                                        <span class="inline-flex rounded-full bg-amber-50 border border-amber-200 px-3 py-0.5 text-xs font-bold text-amber-800">
                                            Kedaluwarsa
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-neutral-100 border border-neutral-300 px-3 py-0.5 text-xs font-bold text-neutral-600">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Tombol Perpanjang -->
                                        <form action="{{ route('admin.members.extend', $mbr) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    title="Perpanjang sesuai durasi paket"
                                                    onclick="return confirm('Perpanjang masa aktif membership {{ $mbr->nama }}?')"
                                                    class="rounded-lg bg-mfc-black px-2.5 py-1.5 text-xs font-bold text-white hover:bg-mfc-dark shadow-xs transition-colors">
                                                Perpanjang
                                            </button>
                                        </form>

                                        <!-- Toggle Status -->
                                        <form action="{{ route('admin.members.toggle-status', $mbr) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="rounded-lg border border-mfc-line px-2.5 py-1.5 text-xs font-semibold text-mfc-mute hover:bg-mfc-field transition-colors">
                                                {{ $mbr->status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <!-- Tombol Hapus -->
                                        <button type="button" 
                                                onclick="openDeleteModal('{{ route('admin.members.destroy', $mbr) }}', '{{ addslashes($mbr->nama) }}', 'member')"
                                                title="Hapus data member"
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
                                <td colspan="8" class="px-6 py-12 text-center text-sm text-mfc-mute">
                                    Tidak ada data member yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($members->hasPages())
                <div class="p-4 sm:p-6 border-t border-mfc-line">
                    {{ $members->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
