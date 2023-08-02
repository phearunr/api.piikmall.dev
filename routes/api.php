<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HydraController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\DataSync\OrderController;
use App\Http\Controllers\DataSync\GoodsController;

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

//use the middleware 'hydra.log' with any request to get the detailed headers, request parameters and response logged in logs/laravel.log

Route::get('hydra', [HydraController::class, 'hydra']);
Route::get('hydra/version', [HydraController::class, 'version']);

Route::apiResource('users', UserController::class)->except(['edit', 'create', 'store', 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::post('users', [UserController::class, 'store']);
Route::put('users/{user}', [UserController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin,user']);
Route::post('users/{user}', [UserController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin,user']);
Route::patch('users/{user}', [UserController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin,user']);
Route::get('me', [UserController::class, 'me'])->middleware('auth:sanctum');
Route::post('login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum', 'ability:admin,super-admin,user'])->group(function () {
   
    Route::apiResource('products', GoodsController::class);
    Route::get('products/{product_id}/quick-edit', [GoodsController::class, 'QuickEdit']);
    Route::put('products/{product_id}/quick-edit', [GoodsController::class, 'QuickUpdate']);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('media', MediaController::class);
    Route::apiResource('roles', RoleController::class)->except(['create', 'edit']);
    Route::apiResource('users.roles', UserRoleController::class)->except(['create', 'edit', 'show', 'update']);
});

require __DIR__ . '/finapp/v1/api.php';
