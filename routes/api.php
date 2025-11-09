<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\AdminAuthController;
use App\Http\Controllers\Api\V1\Admin\AdminClientController;
use App\Http\Controllers\Api\V1\Client\ClientAuthController;
use App\Http\Middleware\ValidateTenant;

// API V1 (header version)
Route::middleware(['api'])->group(function () {

    // ADMIN group
    Route::prefix('admin')->group(function () {

        Route::post('/login', [AdminAuthController::class, 'login']);

        Route::get('/login', function() {
            return ['locale' => App::getLocale()];
        });

        //routes with token and scope required
        Route::middleware(['auth:sanctum', 'scopes:admin.full_access'])
            ->group(function () {

                Route::get('/me', function () {
                    return auth()->user();
                });

                Route::apiResource('clients', AdminClientController::class)->except(['create', 'edit']);
            });
    }); 
    
    
    //CLIENT group
    Route::post('login', [ClientAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'scope:client', ValidateTenant::class])->group(function () {
        //Route::post('/orders', [OrderController::class, 'store']);
    });


});


