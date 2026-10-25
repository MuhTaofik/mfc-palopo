# MFC Palopo - Website MULTI FITNES CENTRE

Website manajemen keanggotaan untuk pusat kebugaran **MULTI FITNES CENTRE**, Kota Palopo.
Proyek mata kuliah **Project Mandiri**.

| | |
|---|---|
| Mitra | Multi Fitnes Pusat Kebugaran (MULTI FITNES CENTRE), Kota Palopo |
| Pengembang | Muh Taofik (NIM 24404170), Teknologi Rekayasa Multimedia |
| Status | Tahap fondasi: proyek Laravel, database, dan struktur folder. Halaman mengikuti desain Figma menyusul. |

## Tech Stack

| Lapisan | Teknologi |
|---|---|
| Backend | Laravel 13 (PHP 8.3) |
| Template | Blade |
| Database | MySQL / MariaDB (Eloquent ORM + migration) |
| Frontend | Tailwind CSS + Flowbite (via Vite) |
| Lingkungan lokal | Laragon (Windows), Node.js, Composer |
| Editor | Google Antigravity / VS Code |
| Version control | Git dan GitHub |

## Struktur Folder (bagian penting)

```
mfc-palopo/
├── app/
│   ├── Http/Controllers/     # pengendali halaman (menyusul)
│   └── Models/               # Admin, Package, Registration, Member
├── config/                   # konfigurasi Laravel (database, auth, dll.)
├── database/
│   ├── migrations/           # pembuatan 4 tabel sesuai ERD
│   ├── seeders/              # data awal (paket)
│   └── schema.sql            # ekspor skema database
├── public/
│   ├── images/               # foto dan logo
│   ├── icons/                # ikon
│   └── fonts/                # font
├── resources/
│   ├── css/                  # CSS (Tailwind)
│   ├── js/                   # JavaScript
│   └── views/
│       ├── layouts/          # kerangka halaman
│       ├── partials/         # navbar, footer
│       ├── pages/            # halaman publik
│       └── admin/            # halaman admin
├── routes/web.php            # daftar route
├── .env.example              # contoh pengaturan lingkungan
└── README.md
```

## Database (ERD Tugas 4)

Empat tabel: `admins`, `packages`, `registrations`, `members`.

- `registrations.package_id` mengacu ke `packages.id_package`
- `members.package_id` mengacu ke `packages.id_package`
- `members.registration_id` mengacu ke `registrations.id_registration`

## Cara Menjalankan di Lokal (Laragon)

Kebutuhan: Laragon (PHP 8.3, MySQL), Composer, Node.js, Git.

1. Taruh proyek di `C:\laragon\www\mfc-palopo` (atau `git clone` dari GitHub ke folder itu).
2. Buka Laragon, klik **Start All** (Apache dan MySQL menyala).
3. Buka terminal di folder proyek, lalu pasang dependensi:
   ```
   composer install
   npm install
   ```
4. Salin pengaturan lingkungan dan buat kunci aplikasi:
   ```
   copy .env.example .env
   php artisan key:generate
   ```
5. Buat database kosong bernama `mfc_palopo`:
   ```
   mysql -u root -e "CREATE DATABASE mfc_palopo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```
6. Di file `.env`, atur:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mfc_palopo
   DB_USERNAME=root
   DB_PASSWORD=
   SESSION_DRIVER=file
   CACHE_STORE=file
   QUEUE_CONNECTION=sync
   ```
7. Buat tabel dan isi data awal:
   ```
   php artisan migrate
   php artisan db:seed
   ```
   (Alternatif: impor `database/schema.sql` lewat HeidiSQL.)
8. Jalankan website:
   ```
   php artisan serve
   ```
   Buka `http://127.0.0.1:8000`.

## Catatan

- File `.env` berisi pengaturan pribadi dan tidak diunggah ke GitHub.
- Akun admin dibuat pada tahap login (kata sandi disimpan sebagai hash bcrypt, tidak ada kata sandi bawaan di repositori).
- Desain acuan: file Figma proyek (halaman gym).