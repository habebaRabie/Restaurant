<?php

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/



require __DIR__ . '/auth.php';

require __DIR__ . '/adminapi.php';


Route::group(['middleware' => ['auth:api']], function () {

    Route::post('/place-order', 'App\Http\Controllers\OrdersController@placeOrder');
    Route::post('/create-cart', 'App\Http\Controllers\CartController@createCart');
    Route::post('/add-to-cart', 'App\Http\Controllers\CartController@addToCart');
    Route::post('/remove-from-cart', 'App\Http\Controllers\CartController@removeFromCart');
});
