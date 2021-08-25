<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;


Route::group(['middleware' => 'guest:admin-api', 'prefix' =>'admin'], function(){
    
    
    Route::get('/login', [AuthenticatedSessionController::class, 'create_admin'])->name('admin.login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store_admin']);

});

Route::group(['middleware' => 'auth:admin-api', 'prefix' =>'admin'], function(){
    Route::get('/register', [RegisteredUserController::class, 'create_admin'])->name('admin.register');
    Route::post('/register', [RegisteredUserController::class, 'store_admin']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy_admin'])->name('admin.logout');
    Route::post('updateadmin/{id}' , 'App\Http\Controllers\AdminController@UpdateAdmin' );
    Route::post('removeadmin/{id}' , 'App\Http\Controllers\AdminController@RemoveAdmins' );
    Route::post('addcategory' , 'App\Http\Controllers\AdminController@AddCategory' );
    Route::post('removecategory/{id}' , 'App\Http\Controllers\AdminController@DeleteCategory');
    Route::post('updatecategory/{id}' , 'App\Http\Controllers\AdminController@UpdateCategory');
    Route::post('additem' , 'App\Http\Controllers\AdminController@AddItem' );
    Route::post('removeitem/{id}' , 'App\Http\Controllers\AdminController@DeleteItem');
    Route::post('updateitem/{id}' , 'App\Http\Controllers\AdminController@UpdateItem');
});

