<?php

use App\Http\Controllers\LoadController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SaleController;
use App\Http\Middleware\TokenVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/token/create', [LoadController::class, 'login'])->name('token-create');

Route::middleware([TokenVerification::class])->group(
    function () {
        Route::post('/load', [LoadController::class, 'load']);
        Route::get('/sellers/{id}', [SellerController::class, 'show']);
        Route::get('/sellers/{id}/sales', [SellerController::class, 'sales']);
        Route::get('/sellers/{id}/contacts', [SellerController::class, 'contacts']);
        Route::get('/sales/{year}', [SaleController::class, 'year']);
    }
);
