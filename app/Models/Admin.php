<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admins';
    protected $primaryKey = 'id_admin';

    protected $fillable = ['username', 'password_hash'];

    protected $hidden = ['password_hash'];

    /**
     * Laravel biasanya membaca kolom "password".
     * Di ERD kita, kolomnya bernama "password_hash", jadi diarahkan di sini.
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }
}
