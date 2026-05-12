<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($barang_masuk) {
            // format tanggal
            $today = date('Ymd');

            // cari kode terakhir hari ini + LOCK biar tidak bentrok
            $last = self::where('kode_transaksi', 'like', "TRM-$today-%")
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $urutan = 1;

            if ($last) {
                $urutan = ((int) substr($last->kode_transaksi, -4)) + 1;
            }

            $barang_masuk->kode_transaksi =
                "TRM-$today-" . str_pad($urutan, 4, '0', STR_PAD_LEFT);
        });
    }

    public function details()
    {
        return $this->hasMany(BarangMasukDetail::class, 'barang_masuk_id');
    }
}

/*
class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'barang_id',
        'jumlah',
        'harga_satuan',
        'harga_total',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
*/
