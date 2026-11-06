<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    protected $table = 'members';
    protected $primaryKey = 'id_member';

    protected $fillable = [
        'nama',
        'kontak',
        'package_id',
        'registration_id',
        'tanggal_daftar',
        'tanggal_berakhir',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_daftar' => 'date',
            'tanggal_berakhir' => 'date',
        ];
    }

    /**
     * Otomatis normalisasi nomor kontak saat disimpan.
     */
    protected function kontak(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Registration::normalizeKontak($value),
        );
    }

    /**
     * Alias accessor tanggal_mulai merujuk ke tanggal_daftar.
     */
    protected function tanggalMulai(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_daftar,
        );
    }

    /**
     * Mengecek apakah masa keanggotaan belum dimulai (tanggal_mulai > hari ini secara kalender).
     */
    protected function isNotStarted(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->tanggal_daftar) {
                    return false;
                }

                return $this->tanggal_daftar->copy()->startOfDay()->gt(Carbon::today());
            },
        );
    }

    /**
     * Mengecek apakah keanggotaan sudah kedaluwarsa berdasarkan tanggal kalender.
     * Kedaluwarsa jika tanggal berakhir sudah lewat kemarin atau sebelumnya.
     */
    protected function isExpired(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->tanggal_berakhir) {
                    return true;
                }

                return $this->tanggal_berakhir->copy()->startOfDay()->lt(Carbon::today());
            },
        );
    }

    /**
     * Mengecek apakah hari ini berada dalam rentang aktif kalender (tanggal_mulai <= hari ini <= tanggal_berakhir).
     */
    protected function isActivePeriod(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->tanggal_daftar || ! $this->tanggal_berakhir) {
                    return false;
                }

                $today = Carbon::today();
                $start = $this->tanggal_daftar->copy()->startOfDay();
                $end = $this->tanggal_berakhir->copy()->startOfDay();

                return $start->lte($today) && $end->gte($today);
            },
        );
    }

    /**
     * Label status keanggotaan berdasarkan aturan bisnis dan tanggal kalender:
     * - 'Nonaktif' jika status model Nonaktif.
     * - 'Belum Dimulai' jika tanggal_mulai > hari ini.
     * - 'Kedaluwarsa' jika tanggal_berakhir < hari ini.
     * - 'Aktif' jika hari ini berada di antara tanggal_mulai dan tanggal_berakhir.
     */
    protected function statusKeanggotaan(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status === 'Nonaktif') {
                    return 'Nonaktif';
                }

                if ($this->is_not_started) {
                    return 'Belum Dimulai';
                }

                if ($this->is_expired) {
                    return 'Kedaluwarsa';
                }

                return 'Aktif';
            },
        );
    }

    /**
     * Menghitung sisa hari keanggotaan sebagai bilangan bulat (integer)
     * berdasarkan selisih tanggal kalender murni tanpa pecahan jam/detik.
     * Jika belum dimulai atau sudah kedaluwarsa, mengembalikan 0 (tidak menampilkan sisa hari sebelum dimulai).
     */
    protected function sisaHari(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->tanggal_berakhir || $this->is_not_started || $this->is_expired) {
                    return 0;
                }

                $today = Carbon::today();
                $end = $this->tanggal_berakhir->copy()->startOfDay();

                return (int) $today->diffInDays($end);
            },
        );
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'id_package');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id', 'id_registration');
    }
}
