@extends('layouts.admin')

@section('title', 'Data Member')
@section('page_title', 'Kelola Data Member Gym')

@section('admin_content')
    <div class="space-y-6">
        
        <!-- Filter & Search Bar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            
            <!-- Filter Tabs -->
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.members.index') }}" 
                   class="rounded-xl px-4 py-2 text-xs font-bold transition-colors {{ !$currentStatus ? 'bg-mfc-black text-white shadow-xs' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-mfc-page' }}">
                    Semua Member
                </a>
                <a href="{{ route('admin.members.index', ['status' => 'Aktif']) }}" 
                   class="rounded-xl px-4 py-2 text-xs font-bold transition-colors {{ $currentStatus === 'Aktif' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-mfc-page' }}">
                    Member Aktif
                </a>
                <a href="{{ route('admin.members.index', ['status' => 'Nonaktif']) }}" 
                   class="rounded-xl px-4 py-2 text-xs font-bold transition-colors {{ $currentStatus === 'Nonaktif' ? 'bg-neutral-800 text-white shadow-xs' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-mfc-page' }}">
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
                           placeholder="Cari nama atau nomor kontak..."
                           class="w-full rounded-xl border border-mfc-line bg-white pl-4 pr-10 py-2 text-xs text-mfc-ink outline-none focus:border-mfc-black">
                    @if($search)
                        <a href="{{ route('admin.members.index', ['status' => $currentStatus]) }}" class="absolute right-3 top-2 text-mfc-mute hover:text-mfc-black text-xs font-bold">
                            &times;
                        </a>
                    @endif
                </div>
                <button type="submit" class="rounded-xl bg-mfc-black px-4 py-2 text-xs font-bold text-white hover:bg-mfc-dark">
                    Cari
                </button>
            </form>

        </div>

        <!-- Tabel Member -->
        <div class="rounded-3xl border border-mfc-line bg-white overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
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
                                    <div class="flex items-center justify-end gap-2">
                                        
                                        <!-- Tombol Perpanjang -->
                                        <form action="{{ route('admin.members.extend', $mbr) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    title="Perpanjang sesuai durasi paket"
                                                    onclick="return confirm('Perpanjang masa aktif membership {{ $mbr->nama }}?')"
                                                    class="rounded-lg bg-mfc-black px-3 py-1.5 text-xs font-bold text-white hover:bg-mfc-dark shadow-xs transition-colors">
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
                <div class="p-6 border-t border-mfc-line">
                    {{ $members->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
