<?php

use App\Http\Controllers\Admin\WhatsappController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// routes/web.php or routes/api.php
Route::get('whatsapp/webhook', [WhatsAppController::class, 'verify']);
Route::post('whatsapp/webhook', [WhatsAppController::class, 'handle']);
