<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/***** PUBLIC ****/
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);

Route::get('/posts/{post}/show', [\App\Http\Controllers\PostController::class, 'show'])
        ->name('posts.show');

/***** AUTHENTICATED ****/
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // posts
    Route::prefix('posts')->group(function () {
        Route::get('/', [\App\Http\Controllers\PostController::class, 'index'])
            ->name('posts.index');

        Route::get('/create', [\App\Http\Controllers\PostController::class, 'create'])
            ->name('posts.create');
        Route::post('/', [\App\Http\Controllers\PostController::class, 'store'])
            ->name('posts.store');

        Route::get('/{post}/edit', [\App\Http\Controllers\PostController::class, 'edit'])
            ->name('posts.edit');
        Route::put('/{post}', [\App\Http\Controllers\PostController::class, 'update'])
            ->name('posts.update');

        Route::delete('/{post}', [\App\Http\Controllers\PostController::class, 'destroy'])
            ->name('posts.destroy');
    });

    // comments
    Route::prefix('comments')->group(function () {
        Route::post('/', [\App\Http\Controllers\CommentController::class, 'store'])
            ->name('comments.store');
        Route::put('/{comment}', [\App\Http\Controllers\CommentController::class, 'update'])
            ->name('comments.update');
        Route::delete('/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])
            ->name('comments.destroy');
    });
});

require __DIR__.'/auth.php';
