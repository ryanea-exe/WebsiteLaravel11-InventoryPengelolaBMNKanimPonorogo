<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'photo',
        'seksi_id',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    // FOTO PROFIL (DEFAULT OTOMATIS)
    protected $appends = ['photo_url', 'last_login_formatted'];
    
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return asset('storage/' . $this->photo);
        }

        return asset('images/default-profile.png');
    }

    public function getLastLoginFormattedAttribute()
    {
        if (!$this->last_login_at) {
            return 'Belum Pernah Login';
        }

        return $this->last_login_at
            ->translatedFormat('d M Y, H:i');
    }

    public function seksi()
    {
        return $this->belongsTo(Seksi::class);
    }

    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }
}
