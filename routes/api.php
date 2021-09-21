<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\showcontroller;



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




    //cart endpoints
    Route::post('/create-cart', 'App\Http\Controllers\CartController@createCart');
    Route::post('/add-to-cart', 'App\Http\Controllers\CartController@addToCart');
    Route::post('/remove-from-cart', 'App\Http\Controllers\CartController@removeFromCart');
    Route::post('/view-cart', 'App\Http\Controllers\CartController@listCartItems');


    //order endpoints
    Route::post('/place-order', 'App\Http\Controllers\OrdersController@placeOrder');
    Route::post('/order-history', [App\Http\Controllers\OrdersController::class, 'History'])->name('History');
    Route::post('/feedback', [App\Http\Controllers\OrdersController::class, 'add_feedback'])->name('add_feedback');
    Route::post('/rating', [App\Http\Controllers\OrdersController::class, 'add_Rating'])->name('add_Rating');
    
    Route::post('/add-comment', [App\Http\Controllers\CommentController::class, 'addComment'])->name('addComment');


    //promo code endpoints
    Route::post('/store', [App\Http\Controllers\PromoCodeController::class, 'store'])->name('store');
    Route::delete('/destroy', [App\Http\Controllers\PromoCodeController::class, 'destroy'])->name('destroy');

    //complaint endpoints
    Route::post('/add-complaint', [App\Http\Controllers\ComplaintsController::class, 'add'])->name('add');
    Route::post('/remove-complaint', [App\Http\Controllers\ComplaintsController::class, 'remove'])->name('remove');
    Route::post('/show-complaints', [App\Http\Controllers\ComplaintsController::class, 'show'])->name('show');


    //address endpoints
    Route::post('/add-addresses', [App\Http\Controllers\AddressController::class, 'addAddresses'])->name('addAddresses');
    Route::post('/add-fav-addresses', [App\Http\Controllers\AddressController::class, 'addFavouriteAddress'])->name('addFavouriteAddress');
    Route::post('/add-another-addresses', [App\Http\Controllers\AddressController::class, 'addAnotherAddress'])->name('addAnotherAddress');

    //item endpoints
    Route::post('/review-iteam', [App\Http\Controllers\It_FeedController::class, 'Add_item_F_R'])->name('Add_item_F_R');
    Route::post('/All-Items', [App\Http\Controllers\ItemController::class, 'GetItem'])->name('GetItem');

});
