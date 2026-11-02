@extends('layouts.admin')

@section('title', 'Ganti Password Admin')
@section('page_title', 'Ganti Password Akun Admin')

@section('admin_content')
    <div class="max-w-xl mx-auto space-y-6">

        <!-- Header Card Info -->
        <div class="rounded-2xl sm:rounded-3xl border border-mfc-line bg-white p-6 sm:p-8 shadow-xs space-y-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-mfc-black text-white flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-black text-mfc-black">Perbarui Password Admin</h2>
                    <p class="text-xs text-mfc-mute">Gunakan kata sandi kuat minimal 8 karakter yang sulit ditebak.</p>
                </div>
            </div>

            <!-- Form Ganti Password -->
            <form action="{{ route('admin.password.update') }}" method="POST" class="space-y-4 sm:space-y-5">
                @csrf
                @method('PUT')

                <!-- Username Aktif (Readonly) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-mfc-dark mb-1.5">
                        Username Admin
                    </label>
                    <input type="text" value="{{ auth()->guard('admin')->user()->username }}" disabled
                           class="w-full rounded-xl border border-mfc-line bg-mfc-page px-4 py-3 text-xs sm:text-sm text-mfc-mute cursor-not-allowed">
                </div>

                <!-- Password Saat Ini -->
                <div>
                    <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-mfc-dark mb-1.5">
                        Password Saat Ini
                    </label>
                    <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                           placeholder="Masukkan password yang sedang digunakan"
                           class="w-full rounded-xl border {{ $errors->has('current_password') ? 'border-mfc-error bg-mfc-error-bg/20' : 'border-mfc-line bg-white' }} px-4 py-3 text-xs sm:text-sm text-mfc-ink outline-none focus:border-mfc-black transition-all min-h-[46px]">
                    @error('current_password')
                        <p class="mt-1 text-xs text-mfc-error font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Baru -->
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-mfc-dark mb-1.5">
                        Password Baru
                    </label>
                    <input type="password" id="password" name="password" required autocomplete="new-password" minlength="8"
                           placeholder="Minimal 8 karakter kombinasi"
                           class="w-full rounded-xl border {{ $errors->has('password') ? 'border-mfc-error bg-mfc-error-bg/20' : 'border-mfc-line bg-white' }} px-4 py-3 text-xs sm:text-sm text-mfc-ink outline-none focus:border-mfc-black transition-all min-h-[46px]">
                    @error('password')
                        <p class="mt-1 text-xs text-mfc-error font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-mfc-dark mb-1.5">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" minlength="8"
                           placeholder="Ulangi password baru persis sama"
                           class="w-full rounded-xl border border-mfc-line bg-white px-4 py-3 text-xs sm:text-sm text-mfc-ink outline-none focus:border-mfc-black transition-all min-h-[46px]">
                </div>

                <div class="pt-3 border-t border-mfc-line flex items-center justify-between gap-3">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center justify-center min-h-[44px] rounded-xl border border-mfc-line bg-white px-5 py-2.5 text-xs font-semibold text-mfc-mute hover:bg-mfc-field transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center min-h-[44px] rounded-xl bg-mfc-black px-6 py-2.5 text-xs font-bold text-white hover:bg-mfc-dark shadow-sm transition-colors">
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection

