<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AbstractorController;

// ==========================================
// 1. PUBLIC API ROUTES
// ==========================================
Route::post('/login', [AuthController::class, 'login']);


// ==========================================
// 2. WEB BROWSER COMPATIBLE ADMIN ROUTES 
// (Bypassed Sanctum Token check for local blade forms testing)
// ==========================================
Route::prefix('admin')->group(function () {
    Route::post('/users/create', [AdminController::class, 'createUser']);
    Route::post('/tickets/create', [AdminController::class, 'createTicket']);
    Route::post('/tickets/{id}/reassign', [AdminController::class, 'reassignTicket']);
    Route::post('/tickets/{id}/review', [AdminController::class, 'reviewTicket']);
});


// ==========================================
// 3. PROTECTED MOBILE API ROUTES (Requires Sanctum Bearer Token)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);

    // Abstractor Mobile Workspaces
    Route::prefix('abstractor')->group(function () {
        Route::get('/my-tasks', [AbstractorController::class, 'getMyTasks']);
        Route::post('/tasks/{id}/update-status', [AbstractorController::class, 'updateStatus']);
        Route::post('/tasks/{id}/log-activity', [AbstractorController::class, 'logActivity']);
        Route::post('/tasks/{id}/escalate', [AbstractorController::class, 'escalateTask']);
        Route::post('/tasks/{id}/submit', [AbstractorController::class, 'submitFinalReport']); // 15MB upload logic
    });
});