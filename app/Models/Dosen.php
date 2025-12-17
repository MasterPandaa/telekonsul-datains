<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\Initials;
use App\Support\ProfilePhoto;
use Illuminate\Support\Str;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'foto',
        'nip',
        'email',
        'alamat',
        'no_hp',
    ];

    /**
     * Relasi dengan user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor untuk mendapatkan nama dari user
     */
    public function getNamaAttribute()
    {
        return $this->user ? $this->user->name : null;
    }

    public function getFotoUrlAttribute(): string
    {
        if (!$this->has_photo) {
            return asset('img/dokter/default.jpg');
        }

        $foto = (string) $this->foto;

        if (Str::startsWith($foto, ['http://', 'https://'])) {
            return $foto;
        }

        return ProfilePhoto::resolveUrl($foto, (int) ($this->user_id ?? 0)) ?? ProfilePhoto::blackDataUrl();
    }

    public function getHasPhotoAttribute(): bool
    {
        return !empty($this->foto) && !Str::contains(strtolower((string) $this->foto), 'default');
    }

    public function getInitialsAttribute(): string
    {
        $nameSource = $this->nama ?? ($this->user ? $this->user->name : null);

        return Initials::from($nameSource, 2);
    }

    /**
     * Relasi dengan penilaian konsultasi
     */
    public function penilaian()
    {
        return $this->hasMany(Konsultasi::class, 'dosen_id');
    }
}