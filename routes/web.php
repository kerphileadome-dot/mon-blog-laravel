<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// Route temporaire pour créer l'admin (à supprimer après usage)
Route::get('/create-admin-account', function () {
    if (\App\Models\User::where('email', 'kerphilesaint@gmail.com')->exists()) {
        return 'Le compte admin existe déjà !';
    }

    \App\Models\User::create([
        'name' => 'Kerphile Saint',
        'email' => 'kerphilesaint@gmail.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);

    return 'Compte admin créé avec succès ! Email: kerphilesaint@gmail.com / Mot de passe: password';
});

// Routes Admin (protégées)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/posts', [DashboardController::class, 'posts'])->name('posts');
    Route::get('/comments', [DashboardController::class, 'comments'])->name('comments');
    Route::post('/comments/{comment}/approve', [DashboardController::class, 'approveComment'])->name('comments.approve');
    Route::post('/comments/{comment}/reject', [DashboardController::class, 'rejectComment'])->name('comments.reject');
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

// Commentaires et Likes (public)
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');

require __DIR__.'/auth.php';
