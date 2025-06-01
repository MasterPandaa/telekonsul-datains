<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Relasi dengan mahasiswa
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }
    
    /**
     * Relasi dengan pasien
     */
    public function pasien()
    {
        return $this->hasOne(Pasien::class, 'email', 'email');
    }
    
    /**
     * Relasi dengan notifikasi
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    
    /**
     * Mendapatkan jumlah notifikasi yang belum dibaca
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}
