<?php

use App\Http\Controllers\Api\LyricController;
use App\Http\Controllers\Api\TwitterBotController;
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

Route::prefix('v1')->group(function () {
    Route::post('auth/register', [\App\Http\Controllers\Api\ApiAuthController::class, 'register']);

    Route::middleware('api.key')->group(function () {
        Route::post('auth/regenerate-key', [\App\Http\Controllers\Api\ApiAuthController::class, 'regenerateKey']);
        Route::get('site-config', [LyricController::class, 'siteConfig']);
        Route::get('categories', [LyricController::class, 'categories']);
        Route::get('songs', [LyricController::class, 'index']);
        Route::get('songs/search', [LyricController::class, 'search']);
        Route::get('songs/{id}', [LyricController::class, 'show']);
        Route::post('mass-selection', [LyricController::class, 'massSelection']);
    });
});

Route::get('openapi.json', [LyricController::class, 'openapi']);

Route::middleware('api.key')->group(function () {
    Route::get('song/search', [LyricController::class, 'searchSong']);
    Route::get('song/{id}', [LyricController::class, 'viewSong']);
});
Route::post('bot/lyric', [TwitterBotController::class, 'get_lyrics']);
