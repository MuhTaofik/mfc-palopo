# Panduan Pengembangan Agen AI — Multi Fitness Center (MFC) Palopo

Dokumen ini berisi panduan dan aturan pengembangan untuk agen AI yang bekerja pada repositori **MFC Palopo**.

---

## 1. Ringkasan Proyek & Tech Stack

- **Aplikasi**: Sistem informasi dan manajemen keanggotaan gym **MULTI FITNES CENTRE (MFC) Palopo**.
- **Backend**: Laravel 13 (PHP 8.3).
- **Database**: MySQL / MariaDB (melalui Laragon di lokal).
- **Frontend**: Blade Templating + Tailwind CSS & Flowbite (via Vite).
- **Autentikasi**: Guard `admin` berbasis model `App\Models\Admin` pada tabel `admins`.

---

## 2. Struktur Database & Model

Terdapat 4 entitas utama:
1. `admins` (`App\Models\Admin`): Akun pengelola gym.
2. `packages` (`App\Models\Package`): Paket keanggotaan gym.
3. `registrations` (`App\Models\Registration`): Pengajuan pendaftaran online oleh pengunjung.
4. `members` (`App\Models\Member`): Anggota resmi gym yang disetujui dari pendaftaran.

> **Aturan Invarian Harga Paket**:
> - Paket Harian: **Rp15.000**
> - Paket Bulanan: **Rp100.000**
> *Harga ini tidak boleh diubah kecuali ada instruksi tertulis.*

---

## 3. Standar Keamanan & Fitur Kritis

### A. Fitur Cek Status Pendaftaran (Keamanan E1)
- **Kode Pendaftaran**: Menggunakan format `MFC-` diikuti 16 karakter acak kriptografis (`random_int`), total 20 karakter.
- **Penyimpanan di Database**: Hanya menyimpan hash deterministik HMAC-SHA256 (`kode_hash`), bukan plaintext.
- **Pencocokan**: Wajib verifikasi ganda (`kode_hash` + nomor kontak persis/exact match, bukan fuzzy `LIKE`).
- **Normalisasi Kontak**: Semua variasi nomor WhatsApp (+62, 62, tanda hubung) dinormalisasi otomatis ke format standar lokal (`0...`).
- **Pesan Kesalahan**: Harus bersifat generik: *"Kode pendaftaran atau nomor WhatsApp tidak sesuai. Silakan periksa kembali."*
- **Rate Limiting**:
  - Middleware `throttle:cek-status` (5 request/menit per IP).
  - Rate limiting per kode (maksimal 3 percobaan/menit per kode di controller sebelum query database).
- **Privasi**: Nama pengunjung wajib disensor/masking (`masked_nama`), tidak mengekspos ID internal atau nomor kontak lengkap.

---

## 4. Perintah Operasional & Pengujian

Gunakan PHP 8.3 yang tersedia pada lingkungan Laragon:

```powershell
# Menjalankan pengujian otomatis
php artisan test

# Menjalankan pengujian spesifik keamanan E1
php artisan test tests/Feature/RegistrationSecurityTest.php

# Memeriksa daftar rute aktif
php artisan route:list
```

---

## 5. Larangan & Integritas Repositori

1. **Dilarang mengeksekusi installer skrip dari URL** (seperti `curl | bash` atau `Invoke-Expression` dari remote URL).
2. **Dilarang memasang paket dependensi baru** tanpa persetujuan eksplisit dari pengguna.
3. **Dilarang mereset atau menghapus data database** (`migrate:fresh` dilarang keras di lingkungan produksi/lokal berisi data).
4. **Dilarang mengubah rancangan antarmuka (UI/UX)** yang sudah disesuaikan dengan desain Figma.
5. **Dilarang melakukan git commit atau push ke remote GitHub** kecuali diminta secara eksplisit oleh pengguna.
