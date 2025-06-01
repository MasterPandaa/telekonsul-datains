<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'konsultasi_id',
        'room_id',
        'is_active',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime'
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
} 