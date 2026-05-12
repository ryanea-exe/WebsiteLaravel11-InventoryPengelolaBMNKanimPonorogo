<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatServis extends Model
{
    use HasFactory;

    protected $table = 'riwayat_servis';

    protected $fillable = [
        'kendaraan_id',
        'tanggal_servis',
        'nama_pengurus',
        'keterangan'
    ];

    protected $casts = [
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    public function Kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
}
