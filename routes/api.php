<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\AdminAuthController;

// API V1 (header version)
Route::middleware(['api'])->group(function () {

    // ADMIN group
    Route::prefix('admin')->group(function () {
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

});








//TODO: to delete
Route::get('/health', App\Http\Controllers\SwaggerTestController::class);