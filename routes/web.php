<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'doLogout'])->name('logout');

    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::post('/books/{book}/favorites', [BookController::class, 'favoriteToggle'])->name('favorites.toggle');
    Route::post('/reviews/{review}/like', [BookController::class, 'likedToggle'])->name('reviews.like');

    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/favorites', function () {
        return 'お気に入り一覧（準備中）';
    })->name('favorites.index');

    Route::get('/genres', function () {
        return 'ジャンル管理（準備中）';
    })->name('genres.index');
});

Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'store'])->name('register');
    Route::post('/login', [AuthController::class, 'doLogin'])->name('login');
});

Route::get('/', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

Route::get('/ranking', function () {
    return 'ランキングページ（準備中）';
})->name('ranking.index');
