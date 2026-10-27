<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - MULTI FITNES CENTRE (MFC) Palopo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-mfc-black min-h-screen flex items-center justify-center p-4 selection:bg-mfc-dark selection:text-white relative overflow-hidden">

    <!-- Background Pattern & Dark Gym Ambience -->
    <div class="absolute inset-0 bg-radial-[at_top] from-neutral-800/80 via-mfc-black to-mfc-black"></div>
    <div class="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-neutral-700/10 blur-3xl"></div>
    <div class="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-neutral-800/20 blur-3xl"></div>

    <div class="relative w-full max-w-md">
        
        <!-- Login Card -->
        <div class="rounded-3xl border border-neutral-800 bg-white p-8 sm:p-10 shadow-2xl">
            
            <!-- Logo Header -->
            <div class="text-center space-y-3">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mfc-black text-white shadow-md">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10v4m3-6v8m3-7v6m6-6v6m3-7v8m3-6v4M2 12h20"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black uppercase tracking-wider text-mfc-black">MULTI FITNES CENTRE</h1>
                    <p class="text-xs font-semibold uppercase tracking-widest text-mfc-mute">Panel Autentikasi Pengelola</p>
                </div>
            </div>

            <!-- Flash Error Message -->
            @if(session('error'))
                <div class="mt-6 rounded-xl border border-mfc-error/20 bg-mfc-error-bg p-3.5 text-xs font-medium text-mfc-error flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-mfc-error" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-3.5 text-xs font-medium text-emerald-800 flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form Login -->
            <form action="{{ route('admin.login.submit') }}" method="POST" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-xs font-bold uppercase tracking-wider text-mfc-dark mb-1.5">
                        Username Admin
                    </label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                           placeholder="Masukkan username Anda"
                           class="w-full rounded-xl border {{ $errors->has('username') ? 'border-mfc-error bg-mfc-error-bg/20' : 'border-mfc-line bg-mfc-page' }} px-4 py-3 text-sm text-mfc-ink outline-none focus:border-mfc-black focus:bg-white transition-all">
                    @error('username')
                        <p class="mt-1 text-xs text-mfc-error font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-mfc-dark mb-1.5">
                        Password
                    </label>
                    <input type="password" id="password" name="password" required
                           placeholder="••••••••"
                           class="w-full rounded-xl border {{ $errors->has('password') ? 'border-mfc-error bg-mfc-error-bg/20' : 'border-mfc-line bg-mfc-page' }} px-4 py-3 text-sm text-mfc-ink outline-none focus:border-mfc-black focus:bg-white transition-all">
                    @error('password')
                        <p class="mt-1 text-xs text-mfc-error font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-mfc-mute">
                        <input type="checkbox" name="remember" class="rounded border-mfc-line text-mfc-black focus:ring-0">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full rounded-xl bg-mfc-black py-3.5 text-xs font-black uppercase tracking-wider text-white shadow-md hover:bg-mfc-dark transition-all active:scale-98">
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="mt-8 border-t border-mfc-line pt-6 text-center">
                <a href="{{ route('home') }}" class="text-xs font-semibold text-mfc-mute hover:text-mfc-black transition-colors inline-flex items-center gap-1.5">
                    &larr; Kembali ke Website Utama
                </a>
            </div>

        </div>

        <p class="text-center text-xs text-neutral-500 mt-6">
            &copy; {{ date('Y') }} MULTI FITNES CENTRE Palopo. Khusus pengelola gym.
        </p>

    </div>

</body>
</html>
