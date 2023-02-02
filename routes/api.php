<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowingController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post("/register", [RegisterController::class, 'register']);
Route::post("/login", [LoginController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', 'Auth\LoginController@logout');
    Route::get("/user", [UserController::class, 'index']);
    Route::get("/postingan", [PostController::class, 'index']);
    Route::post('/insert-post', [PostController::class, 'insert']);
    Route::put('/update-post/{id}', [PostController::class, 'update']);
    Route::delete('/delete-post/{id}', [PostController::class, 'delete']);

    Route::post('/follow-user', [FollowingController::class, 'follow']);
    Route::post('/unfollow-user', [FollowingController::class, 'unfollow']);

    Route::post('/like-post', [LikeController::class, 'like']);
    Route::post('/unlike-post', [LikeController::class, 'unlike']);

    Route::post('/comment-post', [CommentController::class, 'comment']);

    Route::get('/search', [UserController::class, 'search']);
});