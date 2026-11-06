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
   - **Akun Admin**: Dibuat berdasarkan konfigurasi `ADMIN_DEFAULT_USER` dan `ADMIN_DEFAULT_PASSWORD` pada file `.env` (password acak aman jika tidak disetel). Password juga dapat disetel melalui perintah CLI `php artisan admin:reset-password <username>`.

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

## Panduan Deployment & Hardening Produksi (E6)

Gunakan panduan dan checklist berikut saat mempublikasikan aplikasi ke server hosting atau VPS:

### 1. Checklist Konfigurasi Environment (`.env`)
- [ ] Atur `APP_ENV=production` dan `APP_DEBUG=false` untuk mencegah kebocoran informasi (*information disclosure*).
- [ ] Atur `APP_URL=https://nama-domain-anda.com` dengan protokol HTTPS.
- [ ] Buat kunci aplikasi satu kali pada saat instalasi awal:
  ```bash
  php artisan key:generate
  ```
  > ⚠️ **PERINGATAN KRITIS (`APP_KEY`)**: Sekali aplikasi live dan menyimpan data pendaftaran, **DILARANG KERAS** menjalankan ulang `php artisan key:generate`. Kunci ini digunakan oleh fitur **E1** untuk menghasilkan hash deterministik kode pendaftaran (`kode_hash`). Mengubah `APP_KEY` akan merusak verifikasi status seluruh member yang sudah terdaftar.

### 2. Keamanan Database (Gunakan User Non-Root)
- [ ] **Jangan gunakan user `root` tanpa password**. Buat user MySQL khusus (*least privilege*) dengan hak akses terbatas hanya pada database `mfc_palopo`:
  ```sql
  CREATE USER 'mfc_user'@'localhost' IDENTIFIED BY 'Password_Sangat_Kuat_Dan_Unik!';
  GRANT ALL PRIVILEGES ON mfc_palopo.* TO 'mfc_user'@'localhost';
  FLUSH PRIVILEGES;
  ```
- [ ] Perbarui `DB_USERNAME` dan `DB_PASSWORD` pada file `.env` dengan kredensial user tersebut.

### 3. Keamanan Sesi & SSL / HTTPS
- [ ] Cookie sesi secara otomatis diberi flag `Secure` saat `APP_ENV=production` (HTTPS). Pada lingkungan lokal/Laragon (`APP_ENV=local`), flag `Secure` otomatis dinonaktifkan agar pengujian HTTP lokal tetap lancar. Variabel `SESSION_SECURE_COOKIE` pada `.env` sengaja dikomentari agar fallback otomatis ini tidak tertimpa.
- [ ] Jika aplikasi berada di balik reverse proxy (misal: Cloudflare, Nginx, Load Balancer), cantumkan IP proxy pada variabel `TRUSTED_PROXIES` di `.env`. Konfigurasi ini terbaca melalui `config('app.trusted_proxies')` sehingga tetap berfungsi penuh saat `php artisan config:cache` aktif di lingkungan hosting.

### 4. Kredensial Awal Pengelola Admin (E3)
- [ ] Sebelum menjalankan `php artisan db:seed`, tentukan akun dan kata sandi admin yang kuat di `.env`:
  ```dotenv
  ADMIN_DEFAULT_USER=pengelola_mfc
  ADMIN_DEFAULT_PASSWORD=Kata_Sandi_Admin_Kuat_16_Karakter!
  ```
- [ ] Kata sandi admin juga dapat diatur ulang sewaktu-waktu melalui CLI:
  ```bash
  php artisan admin:reset-password <username>
  ```

### 5. Optimasi Performa & Cache Produksi
Jalankan perintah cache berikut pada server produksi agar performa responsif dan efisien:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```