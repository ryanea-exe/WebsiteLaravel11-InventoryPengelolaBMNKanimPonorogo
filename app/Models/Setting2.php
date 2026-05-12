<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting2 extends Model
{
    protected $table = 'settings2'; // ← tambahkan ini

    protected $fillable = [
        'nama_kasubbag_tu',
        'nip_kasubbag_tu',
        'nama_kaurumum_tu',
        'nip_kaurumum_tu',
        'nama_staffbmn_tu',
        'nip_staffbmn_tu',
    ];
}
