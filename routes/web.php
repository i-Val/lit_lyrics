<?php

use App\Http\Controllers\SongController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
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
Route::get('/about', [WebsiteController::class, 'about'])->name('about');
Route::get('/lyric-builder', [WebsiteController::class, 'lyricBuilder'])->name('lyric-builder');
Route::get('/api/songs/search', [WebsiteController::class, 'searchSongJson'])->name('api.songs.search');
Route::post('/lyric-builder/download', [SongController::class, 'lyricBuilderDownload'])->name('lyric-builder.download');
Route::post('lyrics/search', [WebsiteController::class, 'searchSong']);
Route::get('lyric/{id}', [WebsiteController::class, 'viewSong']);
Route::get('lyric/download/{id}', [SongController::class, 'single_download']);

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::get('register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    Route::get('reset-password/{token}', [AuthController::class, 'resetPasswordForm'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::get('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Email Verification
Route::middleware('auth')->group(function () {
    Route::get('verify-email', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->intended('/');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('email/verification-notification', function () {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->intended('/');
        }
        Auth::user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Dashboard Related Routes
|--------------------------------------------------------------------------
|
*/

Route::get('e', [SongController::class, 'extractFromTxt']);

Route::middleware('auth')->group(function () {
    Route::get('lyric/collection/create', [SongController::class, 'collection_form']);
    Route::get('lyric/collection/download', [SongController::class, 'collection_download']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/lyric', [SongController::class, 'create'])->name('dashboard.lyric.create');
    Route::post('/lyric', [SongController::class, 'addSong'])->name('dashboard.lyric.store');
    Route::get('/lyrics', [SongController::class, 'index'])->name('dashboard.lyric.list');
    Route::get('/lyric/{id}/edit', [SongController::class, 'editSong'])->name('dashboard.lyric.edit');
    Route::put('/lyric/{id}', [SongController::class, 'updateSong'])->name('dashboard.lyric.update');
    Route::delete('/lyric/{id}', [SongController::class, 'delete'])->name('dashboard.lyric.delete');

    // User management
    Route::get('/users', [UserManagementController::class, 'index'])->name('dashboard.users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('dashboard.users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('dashboard.users.store');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('dashboard.users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('dashboard.users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('dashboard.users.destroy');
    Route::post('/users/{user}/reset-link', [UserManagementController::class, 'sendResetLink'])->name('dashboard.users.sendResetLink');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('dashboard.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('dashboard.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('dashboard.profile.password');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('dashboard.settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('dashboard.settings.update');

    // Category Management
    Route::resource('categories', \App\Http\Controllers\CategoryController::class)->names('dashboard.categories');

    // Music Sheet Management
    Route::resource('music-sheets', \App\Http\Controllers\MusicSheetController::class)->names('dashboard.music-sheets');
});

