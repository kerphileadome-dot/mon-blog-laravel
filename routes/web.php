<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\SyncExportController;
use Illuminate\Support\Facades\Route;

// Export lecture seule prod → local (actif si SYNC_EXPORT_TOKEN est défini)
Route::middleware('sync.export')->prefix('internal/sync')->group(function () {
    Route::get('/database', [SyncExportController::class, 'database'])->name('sync.database');
    Route::get('/settings', [SyncExportController::class, 'settings'])->name('sync.settings');
    Route::get('/storage-manifest', [SyncExportController::class, 'storageManifest'])->name('sync.storage-manifest');
    Route::get('/storage/{path}', [SyncExportController::class, 'storageFile'])->where('path', '.*')->name('sync.storage-file');
});

// Page d'accueil
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// Recherche, catégories, tags
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');

// Pages statiques
Route::get('/about', [PageController::class, 'about'])->name('about');

// Dashboard visiteur uniquement
Route::middleware('auth:web')->get('/dashboard', fn () => redirect()->route('posts.index'))->name('dashboard');

// Connexion admin (URL secrète : /admin/login?key=ADMIN_LOGIN_KEY)
Route::middleware(['guest:admin', 'admin.login.key'])->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
});
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Routes Google OAuth
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Routes Admin (protégées)
Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/posts', [DashboardController::class, 'posts'])->name('posts');
    Route::get('/comments', [DashboardController::class, 'comments'])->name('comments');
    Route::post('/comments/{comment}/approve', [DashboardController::class, 'approveComment'])->name('comments.approve');
    Route::post('/comments/{comment}/reject', [DashboardController::class, 'rejectComment'])->name('comments.reject');
    Route::post('/comments/{comment}/reply', [DashboardController::class, 'replyToComment'])->name('comments.reply');

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle-block', [UserManagementController::class, 'toggleBlock'])->name('users.toggle-block');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/export-users', [SettingsController::class, 'exportUsers'])->name('settings.export-users');
    Route::get('/settings/export-stats', [SettingsController::class, 'exportStats'])->name('settings.export-stats');

    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store')->middleware('throttle:10,1');
    Route::delete('/media', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::delete('/media/bulk', [MediaController::class, 'bulkDelete'])->name('media.bulk-delete');

    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.delete');
});

// Articles (slug-based, après les routes fixes)
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Utilisateur connecté
Route::middleware('auth:web')->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/posts/{post}/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/favorite', [FavoriteController::class, 'toggle'])->name('posts.favorite');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
