<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Package;
use App\Models\Registration;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Halaman Dashboard Admin MFC Palopo.
     */
    public function index(): View
    {
        $stats = [
            'total_registrations' => Registration::count(),
            'pending_registrations' => Registration::where('status_pendaftaran', 'Menunggu')->count(),
            'accepted_registrations' => Registration::where('status_pendaftaran', 'Diterima')->count(),
            'active_members' => Member::where('status', 'Aktif')->count(),
            'inactive_members' => Member::where('status', 'Nonaktif')->count(),
            'total_packages' => Package::count(),
        ];

        $recentRegistrations = Registration::with('package')
            ->latest('id_registration')
            ->take(5)
            ->get();

        $recentMembers = Member::with('package')
            ->latest('id_member')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentRegistrations' => $recentRegistrations,
            'recentMembers' => $recentMembers,
        ]);
    }
}
