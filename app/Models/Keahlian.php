<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keahlian extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'mahasiswa_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
