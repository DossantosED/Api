<?php

use App\Http\Controllers\API\PostController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('postByUser', [PostController::class,'postByUser']);
Route::get('getAllPosts', [PostController::class,'posts']);
Route::post('insertPost', [PostController::class,'store']);
Route::post('likePost', [PostController::class,'likePost']);
Route::put('updatePost', [PostController::class,'update']);
Route::delete('deletePost', [PostController::class,'destroy']);