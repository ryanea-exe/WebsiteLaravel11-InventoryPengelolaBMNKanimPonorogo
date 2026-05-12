<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuans';

    protected $fillable = [
        'user_id',
        'barang_id',
        'jumlah',
        'keperluan',
        'tanggal_pengajuan',
        'status',
        'tanggal_proses',
        'read_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
