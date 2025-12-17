<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'jenis_kelamin',
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
        $default = ProfilePhoto::transparentDataUrl();

        if (empty($this->foto)) {
            return $default;
        }

        $foto = (string) $this->foto;

        if (Str::startsWith($foto, ['http://', 'https://'])) {
            return $foto;
        }

        return ProfilePhoto::resolveUrl($foto, (int) ($this->user_id ?? 0)) ?? $default;
    }

    public function getHasPhotoAttribute(): bool
    {
        if (empty($this->foto)) {
            return false;
        }

        $foto = (string) $this->foto;

        if (Str::startsWith($foto, ['http://', 'https://'])) {
            return true;
        }

        return ProfilePhoto::exists($foto);
    }

    /**
     * Relasi dengan penilaian konsultasi
     */
    public function penilaian()
    {
        return $this->hasMany(Konsultasi::class, 'dosen_id');
    }
}