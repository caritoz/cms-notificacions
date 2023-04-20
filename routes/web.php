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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

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
    Route::get('/posts', [\App\Http\Controllers\PostController::class, 'index'])
        ->name('posts.index');
    Route::get('/posts/{post}/edit', [\App\Http\Controllers\PostController::class, 'edit'])
        ->name('posts.edit');
    Route::put('/posts/{post}', [\App\Http\Controllers\PostController::class, 'update'])
        ->name('posts.update');

    // comments
    Route::post('comments', [\App\Http\Controllers\CommentController::class, 'store'])
        ->name('comments.store');
    Route::put('comments/{comment}', [\App\Http\Controllers\CommentController::class, 'update'])
        ->name('comments.update');
    Route::delete('comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])
        ->name('comments.destroy');
});

require __DIR__.'/auth.php';
