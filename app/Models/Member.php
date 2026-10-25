<?php

namespace App\Models;

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

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'id_package');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id', 'id_registration');
    }
}
