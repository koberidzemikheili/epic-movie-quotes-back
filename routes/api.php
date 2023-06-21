<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EditUserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LocalizeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\QuoteController;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Quote;
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
		Route::get('/user', function (Request $request) {
			return $request->user();
		});
		Route::post('/logout', [AuthController::class, 'logout']);
		Route::post('/edit', [EditUserController::class, 'update']);
		Route::get('/usermovies', [MovieController::class, 'usermovies']);
		Route::get('/user/{user}', function (User $user) {
			return response()->json(['user' => $user], 201);
		});

		Route::post('/quote', [QuoteController::class, 'store']);
		Route::get('/quote/{quote}', [QuoteController::class, 'index']);
		Route::put('/quote/{quote}', [QuoteController::class, 'update']);
		Route::delete('/quote/{quote}', [QuoteController::class, 'destroy']);
		Route::get('/quote', function () {
			return response()->json(['quotes' => Quote::with(['comments', 'likes'])->get()], 201);
		});

		Route::post('/movie', [MovieController::class, 'store']);
		Route::get('/movie/{movie}', [MovieController::class, 'show']);
		Route::put('/movie/{movie}', [MovieController::class, 'update']);
		Route::delete('/movie/{movie}', [MovieController::class, 'destroy']);
		Route::get('/movie', function () {
			return response()->json(['movies' => Movie::all()], 201);
		});

		Route::get('/genres', function () {
			return response()->json(['genres' => Genre::all()], 201);
		});

		Route::post('/comment', [CommentController::class, 'store']);
		Route::post('/like', [LikeController::class, 'store']);
		Route::delete('/like/{like}', [LikeController::class, 'destroy']);
	});
	Route::get('/auth/redirect', [AuthController::class, 'redirect'])->middleware('web');
	Route::get('/auth/callback', [AuthController::class, 'callback'])->middleware('web');
});
