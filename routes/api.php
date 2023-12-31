<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LocalizeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;

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

	Route::post('/forgot-password', [PasswordController::class, 'postResetEmail'])->name('password.email');
	Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');

	Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
	Route::get('/verify-new-email/{id}/{hash}', [VerificationController::class, 'verifyNewEmail'])->middleware(['signed'])->name('verification.verify_new_email');

	Route::middleware(['auth:sanctum', 'verified'])->group(function () {
		Route::post('/logout', [AuthController::class, 'logout']);
		Route::put('/edit', [UserController::class, 'update']);

		Route::get('/user', [UserController::class, 'getAuthUserData']);
		Route::get('/user/movies', [UserController::class, 'userMovies']);
		Route::get('/users/{user}', [UserController::class, 'showUser']);

		Route::prefix('quote')->group(function () {
			Route::post('/', [QuoteController::class, 'store']);
			Route::get('/{quote}', [QuoteController::class, 'show']);
			Route::put('/{quote}', [QuoteController::class, 'update']);
			Route::delete('/{quote}', [QuoteController::class, 'destroy']);
			Route::get('/', [QuoteController::class, 'getQuotes']);
		});

		Route::prefix('movie')->group(function () {
			Route::post('/', [MovieController::class, 'store']);
			Route::get('/{movie}', [MovieController::class, 'show']);
			Route::put('/{movie}', [MovieController::class, 'update']);
			Route::delete('/{movie}', [MovieController::class, 'destroy']);
			Route::get('/', [MovieController::class, 'index']);
		});

		Route::get('/genres', [GenreController::class, 'index']);
		Route::post('/comment', [CommentController::class, 'store']);
		Route::post('/like', [LikeController::class, 'store']);
		Route::delete('/like', [LikeController::class, 'destroy']);

		Route::post('/notifications', [NotificationController::class, 'store']);
		Route::post('/notifications/{notification}/mark-as-seen', [NotificationController::class, 'markAsSeen']);
		Route::post('/notifications/mark-all-as-seen', [NotificationController::class, 'markAllAsSeen']);
	});
	Route::get('/auth/redirect', [AuthController::class, 'redirect'])->middleware('web');
	Route::get('/auth/callback', [AuthController::class, 'callback'])->middleware('web');
});
