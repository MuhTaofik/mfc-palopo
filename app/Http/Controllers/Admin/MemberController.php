<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Menampilkan daftar data member gym.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $status = $request->query('status');

        $query = Member::with(['package', 'registration'])->latest('id_member');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kontak', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['Aktif', 'Nonaktif'])) {
            $query->where('status', $status);
        }

        $members = $query->paginate(15);

        return view('admin.members.index', [
            'members' => $members,
            'search' => $search,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Memperpanjang masa aktif member.
     */
    public function extend(Request $request, Member $member): RedirectResponse
    {
        $package = $member->package;
        $tambahHari = $package ? $package->durasi_hari : 30;

        // Jika member masih aktif dan tanggal berakhir belum lewat, perpanjang dari tanggal berakhir tersebut
        $currentEnd = Carbon::parse($member->tanggal_berakhir);
        if ($currentEnd->isFuture()) {
            $newEnd = $currentEnd->addDays($tambahHari);
        } else {
            // Jika sudah kedaluwarsa, mulai perpanjangan dari hari ini
            $newEnd = Carbon::today()->addDays($tambahHari);
        }

        $member->update([
            'tanggal_berakhir' => $newEnd->toDateString(),
            'status' => 'Aktif',
        ]);

        return back()->with('success', "Membership {$member->nama} berhasil diperpanjang hingga {$newEnd->translatedFormat('d F Y')}.");
    }

    /**
     * Mengubah status member (Aktif / Nonaktif).
     */
    public function toggleStatus(Member $member): RedirectResponse
    {
        $newStatus = $member->status === 'Aktif' ? 'Nonaktif' : 'Aktif';

        $member->update([
            'status' => $newStatus,
        ]);

        return back()->with('success', "Status member {$member->nama} berhasil diubah menjadi {$newStatus}.");
    }

    /**
     * Menghapus data member secara permanen.
     */
    public function destroy(Member $member): RedirectResponse
    {
        $nama = $member->nama;

        DB::transaction(function () use ($member) {
            $member->delete();
        });

        return back()->with('success', "Data member atas nama \"{$nama}\" berhasil dihapus.");
    }
}
