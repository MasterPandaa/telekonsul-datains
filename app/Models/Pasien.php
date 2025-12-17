<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Konsultasi;
use App\Support\Initials;
use App\Support\ProfilePhoto;
use Illuminate\Support\Str;

class Pasien extends Model
{
    use HasFactory;
    protected $fillable = [
        'nik', 'email', 'alamat', 'no_hp',
        'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'foto',
        'tinggi_badan', 'berat_badan', 'tekanan_darah', 'alergi', 'riwayat_penyakit'
    ];
    
    protected $dates = [
        'tanggal_lahir'
    ];
    
    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi dengan Konsultasi
    public function konsultasi()
    {
        return $this->hasMany(Konsultasi::class);
    }
    
    // Accessor untuk mendapatkan nama dari user
    public function getNamaAttribute()
    {
        return $this->user ? $this->user->name : null;
    }
    
    // Accessor untuk mendapatkan usia pasien
    public function getUsiaAttribute()
    {
        if (!$this->tanggal_lahir) {
            return null;
        }
        
        if (is_string($this->tanggal_lahir)) {
            return \Carbon\Carbon::parse($this->tanggal_lahir)->age;
        }
        
        return $this->tanggal_lahir->age;
    }
    
    // Accessor untuk mendapatkan BMI (Body Mass Index)
    public function getBmiAttribute()
    {
        if (!$this->tinggi_badan || !$this->berat_badan) {
            return null;
        }
        
        $tinggi_m = $this->tinggi_badan / 100; // Konversi cm ke m
        return round($this->berat_badan / ($tinggi_m * $tinggi_m), 2);
    }
    
    // Accessor untuk mendapatkan kategori BMI
    public function getKategoriBmiAttribute()
    {
        $bmi = $this->bmi;
        
        if (!$bmi) {
            return null;
        }
        
        if ($bmi < 18.5) {
            return 'Kurus';
        } elseif ($bmi >= 18.5 && $bmi < 25) {
            return 'Normal';
        } elseif ($bmi >= 25 && $bmi < 30) {
            return 'Gemuk';
        } else {
            return 'Obesitas';
        }
    }
    
    // Accessor untuk mendapatkan URL foto profil
    public function getFotoUrlAttribute()
    {
        if (!$this->has_photo) {
            return asset('img/pasien/default.jpg');
        }

        $foto = (string) $this->foto;

        if (Str::startsWith($foto, ['http://', 'https://'])) {
            return $foto;
        }

        // Legacy: only filename stored, located in public/img/pasien
        if (!Str::contains($foto, '/')) {
            $legacyRelative = 'img/pasien/' . ltrim($foto, '/');
            return file_exists(public_path($legacyRelative))
                ? asset($legacyRelative)
                : ProfilePhoto::blackDataUrl();
        }

        return ProfilePhoto::resolveUrl($foto, (int) ($this->user_id ?? 0)) ?? asset('img/pasien/default.jpg');
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
}