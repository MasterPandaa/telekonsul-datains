<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Konsultasi;

class Pasien extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama', 'nik', 'email', 'alamat', 'no_hp',
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
        if ($this->foto && file_exists(public_path('img/pasien/' . $this->foto))) {
            return asset('img/pasien/' . $this->foto);
        }
        return asset('img/pasien/default.jpg');
    }
} 