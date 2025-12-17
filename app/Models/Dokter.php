<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\ProfilePhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Dokter extends Model
{
    use HasFactory;
    
    protected $table = 'dokters';
    
    protected $fillable = [
        'no_sip', 'no_str', 'email', 'alamat', 'no_hp', 'jenis_kelamin', 
        'tempat_lahir', 'tanggal_lahir', 'foto', 'spesialisasi', 
        'sub_spesialisasi', 'universitas', 'tahun_lulus', 'tempat_praktik',
        'status', 'pengalaman', 'user_id', 'rumah_sakit'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_lulus' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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

        // Prefer new scheme: public/img/profil/{user_id}/fotoprofil.png
        if (Str::contains($foto, 'img/profil/')) {
            return ProfilePhoto::resolveUrl($foto, (int) ($this->user_id ?? 0)) ?? asset('img/dokter/default.jpg');
        }

        if (Str::startsWith($foto, 'storage/')) {
            return ProfilePhoto::resolveUrl($foto, (int) ($this->user_id ?? 0)) ?? ProfilePhoto::blackDataUrl();
        }

        if (Str::startsWith($foto, 'dokters/')) {
            return Storage::disk('public')->exists($foto)
                ? asset('storage/' . ltrim($foto, '/'))
                : ProfilePhoto::blackDataUrl();
        }

        if (Str::startsWith($foto, 'img/')) {
            return file_exists(public_path($foto))
                ? asset($foto)
                : ProfilePhoto::blackDataUrl();
        }

        // Asumsikan path relatif terhadap disk public atau public path
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
        $nameSource = trim($this->nama ?? $this->user->name ?? '');

        if ($nameSource === '') {
            return 'DK';
        }

        $words = preg_split('/\s+/', $nameSource);
        $initials = collect($words)
            ->filter(fn ($word) => $word !== '')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');

        return Str::upper($initials ?: Str::substr($nameSource, 0, 2));
    }
    
    // Accessor untuk mendapatkan nama dari user
    public function getNamaAttribute()
    {
        return $this->user ? $this->user->name : null;
    }
}
 