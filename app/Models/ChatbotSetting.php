<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotSetting extends Model
{
    protected $fillable = [
        'webhook_url',
        'method',
        'timeout',
        'allow_insecure_ssl',
        'auth_type',
        'basic_user',
        'basic_pass',
        'bearer_token',
        'header_key',
        'header_value',
        'jwt_token',
        'webhook_url_prod',
        'webhook_url_test',
    ];
}
