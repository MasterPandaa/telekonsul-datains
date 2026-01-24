<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Konsultasi;
use App\Http\Resources\KonsultasiResource;
use App\Http\Controllers\API\KonsultasiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Chatbot Tools Locations
Route::prefix('chatbot/tools')->group(function () {
    Route::get('/doctors', [App\Http\Controllers\API\ChatbotToolController::class, 'searchDoctors']);
    Route::get('/history', [App\Http\Controllers\API\ChatbotToolController::class, 'getPatientHistory']);
    Route::get('/schedule', [App\Http\Controllers\API\ChatbotToolController::class, 'checkSchedule']);
    Route::post('/book', [App\Http\Controllers\API\ChatbotToolController::class, 'bookConsultation']); // New Route
});