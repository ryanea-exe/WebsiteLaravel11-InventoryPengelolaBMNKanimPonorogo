<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seksi extends Model
{
    protected $table = 'seksi';

    protected $fillable = [
        'seksi',
        'seksi_singkat',
        'nama_kepala',
        'nip_kepala',
    ];

    // relasi ke user
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function kendaraan()
    {
        return $this->hasMany(Kendaraan::class);
    }
}
