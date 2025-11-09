<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\AdminAuthController;
use App\Http\Controllers\Api\V1\Admin\AdminClientController;

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

                Route::post('/clients', [AdminClientController::class, 'store']);
                Route::get('/clients', [AdminClientController::class, 'index']);
                Route::get('/clients/{id}', [AdminClientController::class, 'show']);
                Route::put('/clients/{id}', [AdminClientController::class, 'update']);
            });
    });    

});


