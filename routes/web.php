<?php

use App\Http\Controllers\SongController;
use App\Http\Controllers\WebsiteController;
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

Route::get('/', [WebsiteController::class, 'home'])->name('home');
Route::post('lyrics/search', [WebsiteController::class, 'searchSong']);
Route::get('lyric/{id}', [WebsiteController::class, 'viewSong']);
Route::get('lyric/download/{id}', [SongController::class, 'single_download']);


/*
|--------------------------------------------------------------------------
| Dashboard Related Routes
|--------------------------------------------------------------------------
|
*/
Route::get('/lyric', [SongController::class, 'create'])->name('dashboard.lyric.create');
Route::post('/lyric', [SongController::class, 'addSong'])->name('dashboard.lyric.store');
Route::get('/lyrics', [SongController::class, 'index'])->name('dashboard.lyric.list');






Route::get('lyric/collection/create', [SongController::class, 'collection_form']);
Route::get('lyric/collection/download', [SongController::class, 'collection_download']);
Route::get('e', [SongController::class, 'extractFromTxt']);

