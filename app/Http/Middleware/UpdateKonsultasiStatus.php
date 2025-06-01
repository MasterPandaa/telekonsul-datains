<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\KonsultasiService;

class UpdateKonsultasiStatus
{
    protected $konsultasiService;
    
    public function __construct(KonsultasiService $konsultasiService)
    {
        $this->konsultasiService = $konsultasiService;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Update status konsultasi sebelum menjalankan request
        $this->konsultasiService->updateStatus();
        
        return $next($request);
    }
} 