<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'jumlah',
        'satuan'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'barang_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    // Relasi ke pengajuan detail
    public function pengajuanDetails()
    {
        return $this->hasMany(PengajuanDetail::class, 'barang_id', 'id');
    }

    // Barang keluar = barang yang ada di pengajuan disetujui
    public function barangKeluar()
    {
        return $this->hasMany(PengajuanDetail::class, 'barang_id')
            ->whereHas('pengajuan', function ($q) {
                $q->where('status', 'Disetujui');
            });
    }
}
