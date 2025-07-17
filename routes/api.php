<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use MlSolutions\SanctumTokens\Http\Controllers\SanctumController;
use MlSolutions\SanctumTokens\Http\Controllers\TokenController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. You're free to add
| as many additional routes to this file as your tool may require.
|
*/

Route::get('tokens/{resourceName}/{id}', [SanctumController::class, 'tokens'])->name('ms.sanctum.tokens');
Route::post('tokens/{resourceName}/{id}', [SanctumController::class, 'createToken'])->name('ms.sanctum.createToken');
Route::post('tokens/{resourceName}/{id}/revoke', [SanctumController::class, 'revoke'])->name('ms.sanctum.revoke');

Route::get('tokens-reveal/{id}', [TokenController::class, 'reveal']);
