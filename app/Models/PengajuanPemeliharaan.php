<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_pemeliharaan';

    protected $fillable = [
        'kode_pengajuan',
        'user_id',
        'kendaraan_id',
        'tanggal_pengajuan',
        'status',
        'tanggal_proses',
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

        static::creating(function ($data) {
            $today = date('Ymd');

            $last = self::where('kode_pengajuan', 'like', "PM-$today-%")
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $urutan = 1;

            if ($last) {
                $urutan = ((int) substr($last->kode_pengajuan, -2)) + 1;
            }

            $data->kode_pengajuan =
                "PM-$today-" . str_pad($urutan, 2, '0', STR_PAD_LEFT);
        });
    }

    // relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    // relasi ke pengajuanpemeliharaandetail
    public function details()
    {
        return $this->hasMany(PengajuanPemeliharaanDetail::class, 'pengajuan_id');
    }
}
