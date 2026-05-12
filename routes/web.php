<?php

use App\Http\Controllers\Admin\AdminAlbumController;
use App\Http\Controllers\Admin\AdminArtistController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/artists', [ArtistController::class, 'index'])->name('artists.index');
Route::get('/artists/{artist}', [ArtistController::class, 'show'])->name('artists.show');
Route::get('/releases', [AlbumController::class, 'index'])->name('albums.index');
Route::get('/releases/{album}', [AlbumController::class, 'show'])->name('albums.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::post('/favorites/{artist}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/my-favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/favorites/{artist}', [ProfileController::class, 'destroyFavorite'])->name('profile.favorites.destroy');
    Route::post('/profile/notifications/read', [ProfileController::class, 'markNotificationsRead'])->name('profile.notifications.read');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::resource('artists', AdminArtistController::class)->except('show');
    Route::resource('albums', AdminAlbumController::class)->except('show');
});
