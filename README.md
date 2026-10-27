# MFC Palopo - Website MULTI FITNES CENTRE

Website manajemen keanggotaan untuk pusat kebugaran **MULTI FITNES CENTRE**, Kota Palopo.
Proyek mata kuliah **Project Mandiri**.

| | |
|---|---|
| Mitra | Multi Fitnes Pusat Kebugaran (MULTI FITNES CENTRE), Kota Palopo |
| Pengembang | Muh Taofik (NIM 24404170), Teknologi Rekayasa Multimedia |
| Status | Selesai diimplementasikan: Halaman Beranda, Profil, Paket Harga, Pendaftaran Online, Cek Status, Kontak, dan Panel Admin sesuai desain Figma. |

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
   *Data awal yang di-seed:*
   - **Paket Gym**: Paket Harian (Rp15.000) dan Paket Bulanan (Rp100.000).
   - **Akun Admin Uji**: Username `admin`, Password `admin123`.

8. Jalankan website dan compiler aset:
   ```
   npm run build
   php artisan serve
   ```
   Buka `http://127.0.0.1:8000`.

## Daftar Halaman Website

1. **Beranda** (`/`): Landing page MFC Palopo, hero section, keunggulan, paket latihan, fasilitas, dan CTA pendaftaran.
2. **Profil** (`/profil`): Tentang MFC Palopo, visi & misi, jadwal operasional, dan tata tertib gym.
3. **Paket Harga** (`/paket`): Daftar harga paket membership, tabel komparasi fasilitas, dan FAQ.
4. **Pendaftaran** (`/pendaftaran`): Formulir pendaftaran member baru / perpanjangan online dengan validasi server.
5. **Cek Status** (`/cek-status`): Pencarian mandiri status pendaftaran (Menunggu / Diterima / Ditolak) menggunakan nomor kontak atau ID.
6. **Kontak** (`/kontak`): Informasi kontak, embed Google Maps Kota Palopo, dan formulir kirim pesan.
7. **Panel Admin** (`/admin/login`):
   - Login & Logout khusus pengelola gym (Guard `admin`).
   - Dashboard ringkasan pendaftaran dan anggota.
   - Manajemen verifikasi pendaftaran (Terima / Tolak). Saat disetujui, otomatis membuat data anggota baru di tabel `members`.
   - Manajemen member (Pencarian, filter aktif/nonaktif, dan fitur perpanjang masa aktif membership).