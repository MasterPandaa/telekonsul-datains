<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\Initials;
use App\Support\ProfilePhoto;
use Illuminate\Support\Facades\Storage;
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
        if (!$this->has_photo) {
            return asset('img/dokter/default.jpg');
        }

        $foto = (string) $this->foto;

        if (Str::startsWith($foto, ['http://', 'https://'])) {
            return $foto;
        }

        if (Str::contains($foto, 'img/profil/')) {
            return ProfilePhoto::resolveUrl($foto, (int) ($this->user_id ?? 0)) ?? asset('img/dokter/default.jpg');
        }

        if (Str::startsWith($foto, 'storage/')) {
            return ProfilePhoto::resolveUrl($foto, (int) ($this->user_id ?? 0)) ?? ProfilePhoto::blackDataUrl();
        }

        if (Str::startsWith($foto, 'dosens/')) {
            return Storage::disk('public')->exists($foto)
                ? asset('storage/' . ltrim($foto, '/'))
                : ProfilePhoto::blackDataUrl();
        }

        if (Str::startsWith($foto, 'img/')) {
            return file_exists(public_path($foto))
                ? asset($foto)
                : ProfilePhoto::blackDataUrl();
        }

        if (Storage::disk('public')->exists($foto)) {
            return asset('storage/' . ltrim($foto, '/'));
        }

        return file_exists(public_path($foto)) ? asset($foto) : ProfilePhoto::blackDataUrl();
    }

    public function getHasPhotoAttribute(): bool
    {
        if (empty($this->foto)) {
            return false;
        }

        $foto = (string) $this->foto;
        if (Str::contains(strtolower($foto), 'default')) {
            return false;
        }

        if (Str::startsWith($foto, ['http://', 'https://'])) {
            return true;
        }

        if (Str::contains($foto, 'img/profil/')) {
            return file_exists(public_path(ltrim($foto, '/')));
        }

        return true;
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