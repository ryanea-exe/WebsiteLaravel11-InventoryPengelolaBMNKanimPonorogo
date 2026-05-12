<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanDetail extends Model
{
    protected $table = 'pengajuan_details'; // ✅ FIX DISINI

    protected $fillable = [
        'pengajuan_id',
        'barang_id',
        'jumlah',
        'jumlah_disetujui',
        'status'
    ];

    public function barang()
    {
        return $this->belongsTo(\App\Models\Barang::class);
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id', 'id');
    }
}
