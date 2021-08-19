<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;


Route::prefix('admin')->group(function () {
    Route::post('addadmin' , 'App\Http\Controllers\AdminController@AddAdmins' );
    Route::post('updateadmin/{id}' , 'App\Http\Controllers\AdminController@UpdateAdmin' );
    Route::post('removeadmin/{id}' , 'App\Http\Controllers\AdminController@RemoveAdmins' );
    Route::post('addcategory' , 'App\Http\Controllers\AdminController@AddCategory' );
    Route::post('removecategory/{id}' , 'App\Http\Controllers\AdminController@DeleteCategory');
    Route::post('updatecategory/{id}' , 'App\Http\Controllers\AdminController@UpdateCategory');
    Route::post('additem' , 'App\Http\Controllers\AdminController@AddItem' );
    Route::post('removeitem/{id}' , 'App\Http\Controllers\AdminController@DeleteItem');
    Route::post('updateitem/{id}' , 'App\Http\Controllers\AdminController@UpdateItem');
});

