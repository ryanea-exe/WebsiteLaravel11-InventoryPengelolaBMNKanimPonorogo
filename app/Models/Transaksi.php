<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    
    protected $fillable = [
        'barang_id',
        'tanggal',
        'jenis',
        'jumlah',
        'status'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
