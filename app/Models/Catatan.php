<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catatan extends Model
{
    protected $table = 'catatan';

    protected $fillable = [
        'tanggal',
        'user_id',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}