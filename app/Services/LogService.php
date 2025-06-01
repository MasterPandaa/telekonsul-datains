<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogService
{
    /**
     * Catat aktivitas pengguna
     *
     * @param string $action
     * @param string $description
     * @return \App\Models\Log|null
     */
    public static function record($action, $description = null)
    {
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }
        
        return Log::create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
    
    /**
     * Catat aktivitas login
     *
     * @return \App\Models\Log
     */
    public static function logLogin()
    {
        return self::record('login', 'User telah melakukan login ke sistem');
    }
    
    /**
     * Catat aktivitas logout
     *
     * @return \App\Models\Log
     */
    public static function logLogout()
    {
        return self::record('logout', 'User telah melakukan logout dari sistem');
    }
    
    /**
     * Catat aktivitas CRUD
     *
     * @param string $action create|update|delete
     * @param string $model
     * @param mixed $data
     * @return \App\Models\Log
     */
    public static function logActivity($action, $model, $data = null)
    {
        $modelName = class_basename($model);
        
        $description = match($action) {
            'create' => "User menambahkan data baru pada {$modelName}",
            'update' => "User memperbarui data pada {$modelName}",
            'delete' => "User menghapus data dari {$modelName}",
            default => "User melakukan {$action} pada {$modelName}",
        };
        
        if ($data) {
            $description .= ": " . json_encode($data);
        }
        
        return self::record($action . '_' . strtolower($modelName), $description);
    }
} 