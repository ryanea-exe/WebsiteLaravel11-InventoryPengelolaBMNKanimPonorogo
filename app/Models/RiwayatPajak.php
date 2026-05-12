<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPajak extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pajak';

    protected $fillable = [
        'kendaraan_id',
        'tanggal_pajak',
        'nama_pengurus',
        'keterangan'
    ];

    protected $casts = [
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
}
