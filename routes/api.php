<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EditUserController;
use App\Http\Controllers\LocalizeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PasswordController;
use App\Models\Genre;

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
Route::middleware('localization')->group(function () {
	Route::post('/register', [AuthController::class, 'store']);
	Route::post('/login', [AuthController::class, 'login']);
	Route::post('/localize', [LocalizeController::class, 'store']);

	Route::post('/forgot-password', [PasswordController::class, 'PostResetEmail'])->name('password.email');
	Route::get('/reset-password/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
	Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');

	Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
	Route::middleware('auth:sanctum')->group(function () {
		Route::get('/user', function (Request $request) {
			return $request->user();
		});
		Route::post('/logout', [AuthController::class, 'logout']);
		Route::post('/edit', [EditUserController::class, 'update']);
		Route::post('/add-movie', [MovieController::class, 'store']);
		Route::get('/movie/{movie}', [MovieController::class, 'show']);
		Route::get('/genres', function () {
			return response()->json(['genres' => Genre::all()], 201);
		});
	});
	Route::get('/auth/redirect', [AuthController::class, 'redirect'])->middleware('web');
	Route::get('/auth/callback', [AuthController::class, 'callback'])->middleware('web');
});
