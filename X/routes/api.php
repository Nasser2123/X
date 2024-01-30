<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');

    Route::group(['as' => 'password.', 'middleware' => 'guest'], function () {
        // for send email
        Route::post('forgot-password', 'sendResetLink')->name('email');
        // for change password
        Route::Post('reset-password', 'resetPassword')->name('reset');
    });
});

Route::controller(VerifyEmailController::class)->group(function () {
    Route::group(['as' => 'verification.', 'prefix' => 'email'], function () {

        Route::get('/verify/{id}/{hash}', 'verify')
            ->middleware(['signed'])->name('verify');
    });
});

Route::group(['middleware' => 'auth:sanctum', 'verified'], function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('user/{user}/change-password', [AuthController::class, 'changePassword']);

});
