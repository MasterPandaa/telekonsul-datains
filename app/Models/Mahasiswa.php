<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama', 'nim', 'email', 'alamat', 'no_hp', 'jenis_kelamin', 
        'tempat_lahir', 'tanggal_lahir', 'foto', 'spesialisasi', 
        'semester', 'tahun_masuk', 'ipk', 'status', 'pembimbing', 'user_id',
        'fakultas'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'ipk' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function keahlians()
    {
        return $this->hasMany(Keahlian::class);
    }

    public function prestasis()
    {
        return $this->hasMany(Prestasi::class);
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto && file_exists(public_path($this->foto))) {
            return asset($this->foto);
        }
        return asset('img/mahasiswa/default.jpg');
    }
} 