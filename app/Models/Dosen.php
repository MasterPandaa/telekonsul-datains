<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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

    /**
     * Relasi dengan penilaian konsultasi
     */
    public function penilaian()
    {
        return $this->hasMany(Konsultasi::class, 'dosen_id');
    }
} 