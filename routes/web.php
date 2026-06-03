<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// Routes Google OAuth
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Routes Admin (protégées)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/posts', [DashboardController::class, 'posts'])->name('posts');
    Route::get('/comments', [DashboardController::class, 'comments'])->name('comments');
    Route::post('/comments/{comment}/approve', [DashboardController::class, 'approveComment'])->name('comments.approve');
    Route::post('/comments/{comment}/reject', [DashboardController::class, 'rejectComment'])->name('comments.reject');
    Route::post('/comments/{comment}/reply', [DashboardController::class, 'replyToComment'])->name('comments.reply');

    // Gestion des utilisateurs
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle-block', [UserManagementController::class, 'toggleBlock'])->name('users.toggle-block');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Paramètres et export
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/export-users', [SettingsController::class, 'exportUsers'])->name('settings.export-users');
    Route::get('/settings/export-stats', [SettingsController::class, 'exportStats'])->name('settings.export-stats');

    // Bibliothèque de médias
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/media', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::delete('/media/bulk', [MediaController::class, 'bulkDelete'])->name('media.bulk-delete');
});

// Routes protégées (admin uniquement)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Articles (après les routes fixes)
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Commentaires et Likes (utilisateurs connectés)
Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/favorite', [FavoriteController::class, 'toggle'])->name('posts.favorite');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
});

require __DIR__.'/auth.php';
