<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangBMN extends Model
{
    use HasFactory;

    protected $table = 'barang_bmn';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'merk_type',
        'jumlah',
        'satuan'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi ke pengajuan detail
    public function pengajuanBMNDetails()
    {
        return $this->hasMany(PengajuanBMNDetail::class, 'barang_id', 'id');
    }
}
