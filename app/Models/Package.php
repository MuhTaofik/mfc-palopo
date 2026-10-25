<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $table = 'packages';
    protected $primaryKey = 'id_package';

    protected $fillable = ['nama_paket', 'durasi_hari', 'harga', 'harga_perpanjangan'];

    protected function casts(): array
    {
        return [
            'durasi_hari' => 'integer',
            'harga' => 'integer',
            'harga_perpanjangan' => 'integer',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'package_id', 'id_package');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'package_id', 'id_package');
    }
}
