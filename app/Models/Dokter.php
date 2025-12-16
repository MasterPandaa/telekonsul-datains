<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

        $foto = $this->foto;

        if (Str::startsWith($foto, ['http://', 'https://'])) {
            return $foto;
        }

        if (Str::startsWith($foto, 'storage/')) {
            return asset($foto);
        }

        if (Str::startsWith($foto, 'dokters/')) {
            return asset('storage/' . ltrim($foto, '/'));
        }

        if (Str::startsWith($foto, 'img/')) {
            return asset($foto);
        }

        // Asumsikan path relatif terhadap disk public
        return Storage::disk('public')->exists($foto)
            ? asset('storage/' . ltrim($foto, '/'))
            : asset($foto);
    }

    public function getHasPhotoAttribute(): bool
    {
        return !empty($this->foto) && !Str::contains($this->foto, 'default');
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
 