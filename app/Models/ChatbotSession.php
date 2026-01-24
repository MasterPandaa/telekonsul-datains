<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotSession extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    public function messages()
    {
        return $this->hasMany(ChatbotMessage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
