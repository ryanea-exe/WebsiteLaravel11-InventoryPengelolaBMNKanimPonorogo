<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasukDetail extends Model
{
    protected $fillable = [
        'barang_masuk_id',
        'barang_id',
        'jumlah',
        'harga_satuan',
        'harga_total',
    ];

    public function barang()
    {
        return $this->belongsTo(\App\Models\Barang::class);
    }

    public function barang_masuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuk_id', 'id');
    }
}
