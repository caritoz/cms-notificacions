<?php

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

Route::middleware(['auth:sanctum'])->group(function () {
    // Notifications section
    Route::apiResource('notification-settings', \App\Http\Controllers\NotificationSettingsController::class)->except(['show', 'index']);

    Route::put('/notifications/mark-as-read', [\App\Http\Controllers\ProfileController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::delete('/notifications/delete/{id}', [\App\Http\Controllers\ProfileController::class, 'deleteNotification'])->name('notifications.delete');
});
