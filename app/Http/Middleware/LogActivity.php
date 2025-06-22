<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\LogService;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Proses request
        $response = $next($request);
        
        // Jika user terautentikasi, catat aktivitas
        if (auth()->check() && $request->method() != 'GET') {
            $action = strtolower($request->method());
            $path = $request->path();
            
            // Tentukan jenis aktivitas berdasarkan metode HTTP
            $actionType = match($action) {
                'post' => 'create',
                'put', 'patch' => 'update',
                'delete' => 'delete',
                default => $action
            };
            
            // Catat aktivitas
            LogService::record(
                $actionType . '_' . str_replace('/', '_', $path), 
                "User melakukan {$actionType} pada {$path}"
            );
        }
        
        return $response;
    }
} 