<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\ProfilePhoto;
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

    // Accessor untuk mendapatkan nama dari user
    public function getNamaAttribute()
    {
        return $this->user ? $this->user->name : null;
    }
}
 