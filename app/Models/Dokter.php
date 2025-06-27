<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getFotoUrlAttribute()
    {
        if ($this->foto && file_exists(public_path($this->foto))) {
            return asset($this->foto);
        }
        return asset('img/dokter/default.jpg');
    }
    
    // Accessor untuk mendapatkan nama dari user
    public function getNamaAttribute()
    {
        return $this->user ? $this->user->name : null;
    }
} 