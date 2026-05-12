<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanBMNDetail extends Model
{
    protected $table = 'pengajuan_bmn_detail'; // ✅ FIX DISINI
    
    protected $fillable = [
        'pengajuan_id',
        'barang_id',
        'jumlah',
        'jumlah_disetujui',
        'status'
    ];

    public function barang()
    {
        return $this->belongsTo(\App\Models\BarangBMN::class);
    }

    public function pengajuanBMN()
    {
        return $this->belongsTo(PengajuanBMN::class, 'pengajuan_id', 'id');
    }
}
