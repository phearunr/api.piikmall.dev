<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinApp\OrderController;
use App\Http\Controllers\FinApp\Banks\TCHistoryController;
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

Route::group([
    'middleware' => ['auth:sanctum', 'ability:admin,super-admin,user'],
    'prefix' => 'fin.app/v1',
    ], function($router) {
    
    $router->group(['prefix' => 'stores'], function(){
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::get('/orders/{key}/filter', [OrderController::class, 'filterKey']);
    }); 

    $router->group(['prefix' => 'banks'], function(){
        Route::apiResource('tchistory', TCHistoryController::class);
    }); 
    
});
