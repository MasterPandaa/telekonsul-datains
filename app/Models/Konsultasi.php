<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id',
        'dokter_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'keluhan',
        'keterangan',
        'diagnosa',
        'catatan',
        'nilai',
        'rating',
        'komentar_rating',
        'status',
        'alasan_tolak',
        'alasan_batal',
        'alasan_terlambat',
        'tanggal_baru',
        'jam_mulai_baru',
        'jam_selesai_baru',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'tanggal_baru' => 'date',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
        'jam_mulai_baru' => 'datetime',
        'jam_selesai_baru' => 'datetime',
    ];

    /**
     * Relasi dengan pasien
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    /**
     * Relasi dengan dokter (user)
     */
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }
    
    public function chatRoom()
    {
        return $this->hasOne(ChatRoom::class);
    }
    
    /**
     * Accessor untuk mendapatkan tanggal dalam format Indonesia
     */
    public function getTanggalIndonesiaAttribute()
    {
        return $this->tanggal->isoFormat('D MMMM Y');
    }
    
    /**
     * Accessor untuk mendapatkan jam mulai dan selesai
     */
    public function getWaktuAttribute()
    {
        return $this->jam_mulai . ' - ' . $this->jam_selesai;
    }
} 