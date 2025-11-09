<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\AdminAuthController;

// API V1 (header version)
Route::middleware(['api'])->group(function () {

    // ADMIN group
    Route::prefix('admin')->group(function () {

        Route::post('/login', [AdminAuthController::class, 'login']);

        //routes with token and scope required
        Route::middleware(['auth:sanctum', 'scopes:admin.full_access'])
            ->group(function () {

                Route::get('/me', function () {
                    return auth()->user();
                });
            });
    });    

});








//TODO: to delete
Route::get('/health', App\Http\Controllers\SwaggerTestController::class);