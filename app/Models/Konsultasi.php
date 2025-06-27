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
        'dosen_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'keluhan',
        'keterangan',
        'diagnosa',
        'catatan',
        'nilai',
        'nilai_dosen',
        'nilai_komunikasi',
        'nilai_anamnesis',
        'nilai_diagnosa',
        'nilai_empati',
        'catatan_dosen',
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
        'tanggal' => 'date',
        'tanggal_baru' => 'date',
        'jam_mulai' => 'string',
        'jam_selesai' => 'string',
        'jam_mulai_baru' => 'string',
        'jam_selesai_baru' => 'string',
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
    
    /**
     * Relasi dengan dosen
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
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
    
    /**
     * Mendapatkan nilai rata-rata dari semua kategori nilai
     */
    public function getNilaiRataRata()
    {
        $nilai = 0;
        $count = 0;
        
        if ($this->nilai_komunikasi) {
            $nilai += $this->nilai_komunikasi;
            $count++;
        }
        
        if ($this->nilai_anamnesis) {
            $nilai += $this->nilai_anamnesis;
            $count++;
        }
        
        if ($this->nilai_diagnosa) {
            $nilai += $this->nilai_diagnosa;
            $count++;
        }
        
        if ($this->nilai_empati) {
            $nilai += $this->nilai_empati;
            $count++;
        }
        
        return $count > 0 ? round($nilai / $count) : null;
    }
} 