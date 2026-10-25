<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    /** Sesuai ERD, tabel ini tidak punya kolom updated_at. */
    public const UPDATED_AT = null;

    protected $table = 'registrations';
    protected $primaryKey = 'id_registration';

    protected $fillable = [
        'nama',
        'kontak',
        'alamat',
        'package_id',
        'tanggal_mulai',
        'jenis_pendaftaran',
        'status_pendaftaran',
        'pesan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'id_package');
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class, 'registration_id', 'id_registration');
    }
}
