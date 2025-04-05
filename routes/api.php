<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BasketOrderController;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::controller(BasketOrderController::class)->prefix('/basket')->group(function () {
    Route::post('/add', 'add');
    Route::post('/preview', 'preview');
    Route::post('/campaign/{order}', 'campaignApply');
    Route::delete('/{order}', 'canceled');
});
