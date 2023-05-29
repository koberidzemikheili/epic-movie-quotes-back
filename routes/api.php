<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::get('/auth/redirect', [AuthController::class, 'redirect'])->middleware('web');
Route::get('/auth/callback', [AuthController::class, 'callback'])->middleware('web');

Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
Route::middleware('auth:sanctum')->group(function () {
	Route::get('/user', function (Request $request) {
		return $request->user();
	});
	Route::post('/logout', [AuthController::class, 'logout']);
});
