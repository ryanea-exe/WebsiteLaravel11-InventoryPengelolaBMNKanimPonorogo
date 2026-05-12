<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuans';

     protected $fillable = [
    'kode_pengajuan',
    'user_id',
    'tanggal_pengajuan',
    'keperluan',
    'status',
    'tanggal_proses',
    'keterangan_proses',
    'read_at'
    ];

    protected $casts = [
    'tanggal_pengajuan' => 'datetime',
    'tanggal_proses'    => 'datetime',
    'created_at'        => 'datetime',
    'updated_at'        => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pengajuan) {
            // format tanggal
            $today = date('Ymd');

            // cari kode terakhir hari ini + LOCK biar tidak bentrok
            $last = self::where('kode_pengajuan', 'like', "TRK-$today-%")
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $urutan = 1;

            if ($last) {
                $urutan = ((int) substr($last->kode_pengajuan, -4)) + 1;
            }

            $pengajuan->kode_pengajuan =
                "TRK-$today-" . str_pad($urutan, 4, '0', STR_PAD_LEFT);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(PengajuanDetail::class, 'pengajuan_id', 'id');
    }
}
