<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    /**
     * Halaman Beranda / Landing Page MFC Palopo.
     */
    public function home(): View
    {
        $packages = Package::orderBy('durasi_hari', 'asc')->get();

        return view('pages.home', [
            'packages' => $packages,
        ]);
    }

    /**
     * Halaman Profil / Tentang MFC Palopo.
     */
    public function profil(): View
    {
        return view('pages.profil');
    }

    /**
     * Halaman Paket Harga Gym MFC Palopo.
     */
    public function paket(): View
    {
        $packages = Package::orderBy('durasi_hari', 'asc')->get();

        return view('pages.paket', [
            'packages' => $packages,
        ]);
    }

    /**
     * Halaman Informasi Kontak & Lokasi MFC Palopo.
     */
    public function kontak(): View
    {
        return view('pages.kontak');
    }
}
