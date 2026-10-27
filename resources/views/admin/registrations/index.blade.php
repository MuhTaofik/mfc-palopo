@extends('layouts.admin')

@section('title', 'Data Pendaftaran')
@section('page_title', 'Kelola Data Pendaftaran')

@section('admin_content')
    <div class="space-y-6">
        
        <!-- Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2 border-b border-mfc-line pb-4">
            <a href="{{ route('admin.registrations.index') }}" 
               class="rounded-xl px-4 py-2 text-xs font-bold transition-colors {{ !$currentStatus ? 'bg-mfc-black text-white shadow-xs' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-mfc-page' }}">
                Semua Pendaftaran
            </a>
            <a href="{{ route('admin.registrations.index', ['status' => 'Menunggu']) }}" 
               class="rounded-xl px-4 py-2 text-xs font-bold transition-colors {{ $currentStatus === 'Menunggu' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-mfc-page' }}">
                Menunggu
            </a>
            <a href="{{ route('admin.registrations.index', ['status' => 'Diterima']) }}" 
               class="rounded-xl px-4 py-2 text-xs font-bold transition-colors {{ $currentStatus === 'Diterima' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-mfc-page' }}">
                Diterima
            </a>
            <a href="{{ route('admin.registrations.index', ['status' => 'Ditolak']) }}" 
               class="rounded-xl px-4 py-2 text-xs font-bold transition-colors {{ $currentStatus === 'Ditolak' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white border border-mfc-line text-mfc-mute hover:bg-mfc-page' }}">
                Ditolak
            </a>
        </div>

        <!-- Tabel Pendaftaran -->
        <div class="rounded-3xl border border-mfc-line bg-white overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-mfc-page border-b border-mfc-line text-xs uppercase tracking-wider text-mfc-mute font-bold">
                        <tr>
                            <th class="px-6 py-4">ID & Tgl Daftar</th>
                            <th class="px-6 py-4">Nama Calon Member</th>
                            <th class="px-6 py-4">Kontak & Alamat</th>
                            <th class="px-6 py-4">Paket Dipilih</th>
                            <th class="px-6 py-4">Rencana Mulai</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
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
                                    <span class="block text-xs text-mfc-mute mt-1">Rp {{ number_format($reg->package->harga ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-mfc-dark">
                                    {{ $reg->tanggal_mulai ? $reg->tanggal_mulai->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($reg->status_pendaftaran === 'Menunggu')
                                        <span class="inline-flex rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-xs font-bold text-amber-800">
                                            Menunggu
                                        </span>
                                    @elseif($reg->status_pendaftaran === 'Diterima')
                                        <span class="inline-flex rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-bold text-emerald-800">
                                            Diterima
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-rose-50 border border-rose-200 px-3 py-1 text-xs font-bold text-rose-800">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($reg->status_pendaftaran === 'Menunggu')
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route('admin.registrations.accept', $reg) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        onclick="return confirm('Konfirmasi terima pendaftaran {{ $reg->nama }} dan buat akun member aktif?')"
                                                        class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-emerald-700 shadow-xs transition-colors">
                                                    Terima
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.registrations.reject', $reg) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        onclick="return confirm('Tolak pendaftaran {{ $reg->nama }}?')"
                                                        class="rounded-lg border border-mfc-line px-3 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-mfc-mute italic">Telah Diproses</span>
                                    @endif
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
                <div class="p-6 border-t border-mfc-line">
                    {{ $registrations->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
