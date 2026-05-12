<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings'; // ← tambahkan ini

    protected $fillable = [
        'logo',
        'login_bg',
        'nama_aplikasi',
        'nama_aplikasi2',
        'subnama_aplikasi',
        'login_opening_text',
        'sidebar_color',
        'sidebar_text_color',
        'sidebar_hover_color',
        'format_cetak',
    ];
}
