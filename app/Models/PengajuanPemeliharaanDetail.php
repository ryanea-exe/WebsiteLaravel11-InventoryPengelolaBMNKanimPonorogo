<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPemeliharaanDetail extends Model
{
    protected $table = 'pengajuan_pemeliharaan_detail'; // ✅ FIX DISINI

    protected $fillable = [
        'pengajuan_id',
        'keperluan',
        'estimasi_biaya'
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPemeliharaan::class, 'pengajuan_id');
    }
}
