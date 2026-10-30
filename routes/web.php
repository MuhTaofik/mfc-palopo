<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Halaman Publik (MULTI FITNES CENTRE Palopo)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/profil', [PublicController::class, 'profil'])->name('profil');
Route::get('/paket', [PublicController::class, 'paket'])->name('paket');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('kontak');

/*
|--------------------------------------------------------------------------
| Rute Pendaftaran Online & Cek Status Mandiri
|--------------------------------------------------------------------------
*/
Route::get('/pendaftaran', [RegistrationController::class, 'create'])->name('pendaftaran');
Route::post('/pendaftaran', [RegistrationController::class, 'store'])->name('pendaftaran.store');
Route::get('/cek-status', [RegistrationController::class, 'checkStatus'])->name('cek-status');

/*
|--------------------------------------------------------------------------
| Rute Autentikasi & Panel Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Rute yang Dilindungi Autentikasi Admin
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Manajemen Pendaftaran
        Route::get('/registrasi', [AdminRegistrationController::class, 'index'])->name('registrations.index');
        Route::patch('/registrasi/{registration}/accept', [AdminRegistrationController::class, 'accept'])->name('registrations.accept');
        Route::patch('/registrasi/{registration}/reject', [AdminRegistrationController::class, 'reject'])->name('registrations.reject');
        Route::delete('/registrasi/{registration}', [AdminRegistrationController::class, 'destroy'])->name('registrations.destroy');

        // Manajemen Member
        Route::get('/member', [AdminMemberController::class, 'index'])->name('members.index');
        Route::patch('/member/{member}/extend', [AdminMemberController::class, 'extend'])->name('members.extend');
        Route::patch('/member/{member}/toggle-status', [AdminMemberController::class, 'toggleStatus'])->name('members.toggle-status');
        Route::delete('/member/{member}', [AdminMemberController::class, 'destroy'])->name('members.destroy');
    });
});

Route::view('/tes-tampilan', 'tes-tampilan');