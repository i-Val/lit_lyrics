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

Route::get('/song/add', function () {
    return view('add-song');
});
Route::post('/song/add', [SongController::class, 'addSong']);

Route::post('song/search', [SongController::class, 'searchSong']);

Route::get('song/{id}', [SongController::class, 'viewSong']);
Route::get('download', [SongController::class, 'download']);
Route::get('vanila-download', [SongController::class, 'vanila_download']);

