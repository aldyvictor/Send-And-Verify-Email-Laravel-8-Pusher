<?php

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
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('articles', [ArticleController::class, "store"]);
Route::post('gsignin', [AuthController::class, "gsignin"]);

Route::get('test', [TestController::class, "test"])->name('test')->middleware(['auth:sanctum', 'isarticle.owner']);
Route::post('/login', [AuthController::class, "login"])->name('auth.login');
Route::post('/register', [AuthController::class, "register"])->name("auth.register");

Route::middleware(['auth:sanctum'] )->group(function() {
    Route::apiResource('articles', ArticleController::class);
    
    Route::get('users/me', [UserController::class, 'info']);
});
