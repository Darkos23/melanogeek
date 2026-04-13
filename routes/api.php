<?php

use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExploreController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MelanoGeek API Routes
|--------------------------------------------------------------------------
| Toutes les réponses sont en JSON.
| Auth : Bearer token via Laravel Sanctum.
|--------------------------------------------------------------------------
*/

// ══════════════════════════════════════════════════════════════
// ROUTES PUBLIQUES (sans authentification)
// ══════════════════════════════════════════════════════════════

Route::get('/home',                    [HomeController::class,  'index']);
Route::get('/about',                   [AboutController::class, 'index']);
Route::get('/explore',                 [ExploreController::class, 'index']);
Route::get('/users/{username}',        [ProfileController::class, 'show']);
Route::get('/users/{username}/posts',  [ProfileController::class, 'posts']);
Route::get('/posts/{post}',            [PostController::class,   'show']);

// ══════════════════════════════════════════════════════════════
// AUTHENTIFICATION
// ══════════════════════════════════════════════════════════════

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,10');
    Route::post('/login',    [AuthController::class, 'login'])->middleware('throttle:10,5');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me',      [AuthController::class, 'me']);
    });
});

// ══════════════════════════════════════════════════════════════
// ROUTES PROTÉGÉES (authentification requise)
// ══════════════════════════════════════════════════════════════

Route::middleware('auth:sanctum')->group(function () {

    // Feed
    Route::get('/feed', [FeedController::class, 'index']);

    // Posts
    Route::post('/posts',                  [PostController::class, 'store'])->middleware('throttle:10,1');
    Route::post('/posts/{post}/like',      [PostController::class, 'like'])->middleware('throttle:60,1');
    Route::patch('/posts/{post}/publish',  [PostController::class, 'publish']);
    Route::delete('/posts/{post}',         [PostController::class, 'destroy']);

    // Profil (propre compte)
    Route::patch('/profile',   [ProfileController::class, 'update']);
    Route::delete('/profile',  [ProfileController::class, 'destroy']);

    // Follow / Unfollow
    Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])->middleware('throttle:30,1');
});

// ── Commentaires ──────────────────────────────────────────────
Route::get('/posts/{post}/comments',  [\App\Http\Controllers\Api\CommentController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts/{post}/comments',          [\App\Http\Controllers\Api\CommentController::class, 'store'])->middleware('throttle:20,1');
    Route::delete('/comments/{comment}',           [\App\Http\Controllers\Api\CommentController::class, 'destroy']);
});

// ── Notifications ──────────────────────────────────────────────
Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
    Route::get('/',           [\App\Http\Controllers\Api\NotificationController::class, 'index']);
    Route::get('/unread',     [\App\Http\Controllers\Api\NotificationController::class, 'unreadCount']);
    Route::post('/read-all',  [\App\Http\Controllers\Api\NotificationController::class, 'markAllRead']);
    Route::post('/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markRead']);
});

// ── Messagerie ─────────────────────────────────────────────────
Route::middleware('auth:sanctum')->prefix('messages')->group(function () {
    Route::get('/',                    [\App\Http\Controllers\Api\MessageController::class, 'conversations']);
    Route::get('/unread',              [\App\Http\Controllers\Api\MessageController::class, 'unreadCount']);
    Route::get('/{username}',          [\App\Http\Controllers\Api\MessageController::class, 'show']);
    Route::post('/{username}',         [\App\Http\Controllers\Api\MessageController::class, 'store'])->middleware('throttle:30,1');
    Route::delete('/{message}',        [\App\Http\Controllers\Api\MessageController::class, 'destroy']);
});

// ── Stories ────────────────────────────────────────────────────
Route::get('/stories/{user:username}',  [StoryController::class, 'show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/stories',              [StoryController::class, 'index']);
    Route::post('/stories',             [StoryController::class, 'store']);
    Route::delete('/stories/{story}',   [StoryController::class, 'destroy']);
});
