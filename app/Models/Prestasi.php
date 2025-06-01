<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'tahun', 'mahasiswa_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
