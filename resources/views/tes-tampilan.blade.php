<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes Tampilan | MFC Palopo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-mfc-page font-sans text-mfc-ink antialiased">
    <main class="mx-auto max-w-3xl p-10">
        <h1 class="text-4xl font-extrabold">MULTI FITNES CENTRE</h1>
        <p class="mt-2 text-mfc-mute">
            Jika huruf ini terlihat seperti Inter dan warnanya sesuai, Tailwind berhasil terpasang.
        </p>

        <div class="mt-6 flex flex-wrap gap-3">
            <button class="rounded-lg bg-mfc-dark px-5 py-3 text-sm font-semibold text-white">Tombol utama</button>
            <button class="rounded-lg border border-mfc-line bg-white px-5 py-3 text-sm font-semibold">Tombol garis tepi</button>
            <button class="rounded-lg bg-mfc-error-bg px-5 py-3 text-sm font-semibold text-mfc-error">Tombol bahaya</button>
        </div>

        <h2 class="mt-10 text-xl font-bold">Uji Flowbite (modal)</h2>
        <button data-modal-target="tes-modal" data-modal-toggle="tes-modal" type="button"
                class="mt-3 rounded-lg bg-mfc-dark px-5 py-3 text-sm font-semibold text-white">
            Buka modal
        </button>

        <div id="tes-modal" tabindex="-1" aria-hidden="true"
             class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0">
            <div class="relative max-h-full w-full max-w-md p-4">
                <div class="relative rounded-lg bg-white p-6 shadow">
                    <h3 class="text-lg font-bold">Modal berhasil muncul</h3>
                    <p class="mt-2 text-sm text-mfc-mute">Flowbite berfungsi.</p>
                    <button data-modal-hide="tes-modal" type="button"
                            class="mt-4 rounded-lg border border-mfc-line px-4 py-2 text-sm font-semibold">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>