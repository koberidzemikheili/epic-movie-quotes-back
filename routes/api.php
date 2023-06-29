<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EditUserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LocalizeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
use App\Http\Resources\GenreResource;
use App\Http\Resources\MovieResource;
use App\Http\Resources\UserResource;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;

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
		Route::post('/logout', [AuthController::class, 'logout']);
		Route::post('/edit', [EditUserController::class, 'update']);

		Route::get('/user', [UserController::class, 'getAuthUserData']);
		Route::get('/usermovies', [UserController::class, 'usermovies']);
		Route::get('/user/{user}', function (User $user) {
			return response()->json(['user' => new UserResource($user)], 200);
		});

		Route::post('/quote', [QuoteController::class, 'store']);
		Route::get('/quote/{quote}', [QuoteController::class, 'index']);
		Route::put('/quote/{quote}', [QuoteController::class, 'update']);
		Route::delete('/quote/{quote}', [QuoteController::class, 'destroy']);
		Route::get('/quote', [QuoteController::class, 'getQuotes']);

		Route::post('/movie', [MovieController::class, 'store']);
		Route::get('/movie/{movie}', [MovieController::class, 'show']);
		Route::put('/movie/{movie}', [MovieController::class, 'update']);
		Route::delete('/movie/{movie}', [MovieController::class, 'destroy']);
		Route::get('/movie', function () {
			return response()->json(['movies' => MovieResource::collection(Movie::all())], 200);
		});

		Route::get('/genres', function () {
			return response()->json(['genres' => GenreResource::collection(Genre::all())], 200);
		});

		Route::post('/comment', [CommentController::class, 'store']);
		Route::post('/like', [LikeController::class, 'store']);
		Route::delete('/like/{like}', [LikeController::class, 'destroy']);

		Route::post('/notifications', [NotificationController::class, 'store']);
		Route::post('/notifications/{id}', [NotificationController::class, 'markAsSeen']);
		Route::post('/notifications-mark-all-as-seen', [NotificationController::class, 'markAllAsSeen']);
	});
	Route::get('/auth/redirect', [AuthController::class, 'redirect'])->middleware('web');
	Route::get('/auth/callback', [AuthController::class, 'callback'])->middleware('web');
});
