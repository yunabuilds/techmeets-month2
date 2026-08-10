<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardPostController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('posts', PostController::class);

 });

    Route::get('/board', [BoardPostController::class, 'index'])->name('board.index');
    Route::get('/board/create', [BoardPostController::class, 'create'])->name('board.create');
    Route::post('/board', [BoardPostController::class, 'store'])->name('board.store');
    Route::get('/board/{board_post}', [BoardPostController::class, 'show'])->name('board.show');

Route::middleware('auth')->group(function () {
    Route::delete('/board/{board_post}', [BoardPostController::class, 'destroy'])->name('board.destroy');
});

require __DIR__.'/auth.php';
