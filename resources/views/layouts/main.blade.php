<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'MULTI FITNES CENTRE (MFC) Palopo - Pusat Kebugaran & Gym')</title>
    <meta name="description" content="@yield('meta_description', 'MULTI FITNES CENTRE (MFC) Palopo adalah pusat kebugaran modern di Kota Palopo dengan peralatan lengkap, suasana nyaman, dan harga paket latihan terjangkau.')">
    
    <!-- Vite Assets (Tailwind CSS 4, Flowbite, Inter Font) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-mfc-page font-sans text-mfc-ink antialiased flex min-h-screen flex-col selection:bg-mfc-dark selection:text-white overflow-x-hidden">

    <!-- Navbar Utama -->
    @include('partials.navbar')

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mx-auto mt-4 w-full max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mx-auto mt-4 w-full max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between rounded-xl border border-mfc-error/20 bg-mfc-error-bg p-4 text-mfc-error shadow-sm" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0 text-mfc-error" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Konten Halaman -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Utama -->
    @include('partials.footer')

    @stack('scripts')
</body>
</html>
