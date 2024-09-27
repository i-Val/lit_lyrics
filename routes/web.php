<?php

use App\Http\Controllers\SongController;
use App\Models\Song;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('landing');
});
Route::get('/search', function () {
    return view('search-results');
});
Route::get('/details', function () {
    return view('lyric-details');
});

Route::get('/lyric', [SongController::class, 'create']);
Route::post('/lyric', [SongController::class, 'addSong']);
Route::get('/lyrics', [SongController::class, 'index']);

Route::post('lyrics/search', [SongController::class, 'searchSong']);

Route::get('lyric/{id}', [SongController::class, 'viewSong']);
Route::get('download', [SongController::class, 'download']);
Route::get('lyric/download/{id}', [SongController::class, 'single_download']);
Route::get('lyric/collection/create', [SongController::class, 'collection_form']);
Route::get('lyric/collection/download', [SongController::class, 'collection_download']);

