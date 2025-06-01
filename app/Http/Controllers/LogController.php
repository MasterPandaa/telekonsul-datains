<?php
namespace App\Http\Controllers;
use App\Models\DatabaseLog;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function databaseLog() {
        $logs = DatabaseLog::with('user')->latest()->paginate(30);
        return view('admin.log.database', compact('logs'));
    }
    public function systemLog() {
        $logPath = storage_path('logs/laravel.log');
        $logs = File::exists($logPath) ? array_reverse(explode("\n", File::get($logPath))) : [];
        return view('admin.log.system', compact('logs'));
    }
} 