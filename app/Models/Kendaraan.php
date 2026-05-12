<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraan';

    protected $fillable = [
        'nomor_polisi',
        'nama_kendaraan',
        'jenis_kendaraan',
        'tahun',
        'seksi_id',
        'tanggal_pajak_berkala',
        'rentang_waktu_servis',
        'keterangan'
    ];

    protected $casts = [
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    public function seksi()
    {
        return $this->belongsTo(Seksi::class);
    }

    public function riwayatPajak()
    {
        return $this->hasMany(RiwayatPajak::class, 'kendaraan_id', 'id');
    }

    public function riwayatServis()
    {
        return $this->hasMany(RiwayatServis::class, 'kendaraan_id', 'id');
    }
}
