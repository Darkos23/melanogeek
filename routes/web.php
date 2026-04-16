<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PostAdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/communaute', [ExploreController::class, 'index'])->name('community');
Route::get('/offline', fn() => view('pages.offline'))->name('offline');

Route::get('/a-propos', function () {
    $settings = [
        'about_title' => \App\Models\Setting::get('about_title', 'A propos de MelanoGeek'),
        'about_tagline' => \App\Models\Setting::get('about_tagline', ''),
        'about_mission' => \App\Models\Setting::get('about_mission', ''),
        'about_story' => \App\Models\Setting::get('about_story', ''),
        'about_values' => \App\Models\Setting::get('about_values', ''),
    ];

    return view('pages.about', compact('settings'));
})->name('about');

// Public profiles
Route::get('/@{user:username}', [ProfileController::class, 'show'])->name('profile.show');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

// Create post (auth required)
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
// Forum
Route::get('/forum', fn() => view('forum.index'))->name('forum.index');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => redirect()->route('blog.index'))->name('dashboard');

    // Account settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications');
    Route::get('/notifications/dropdown', [NotificationsController::class, 'dropdown'])->name('notifications.dropdown');
    Route::get('/notifications/unread-count', [NotificationsController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/read-all', [NotificationsController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'markRead'])->name('notifications.read');

    // Push subscriptions
    Route::post('/push/subscribe', [PushSubscriptionController::class, 'store'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');

    // Posts
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::patch('/posts/{post}/publish', [PostController::class, 'publish'])->name('posts.publish');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});

// Public post pages - APRÈS les routes spécifiques
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post}/data', [PostController::class, 'data'])->name('posts.data');

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/applications', [AdminController::class, 'applications'])->name('applications');
    Route::patch('/applications/{user}/approve', [AdminController::class, 'applicationApprove'])->name('applications.approve');
    Route::patch('/applications/{user}/reject', [AdminController::class, 'applicationReject'])->name('applications.reject');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit', [AdminController::class, 'userEdit'])->name('users.edit');
    Route::patch('/users/{user}', [AdminController::class, 'userUpdate'])->name('users.update');
    Route::patch('/users/{user}/toggle', [AdminController::class, 'userToggle'])->name('users.toggle');
    Route::patch('/users/{user}/verify', [AdminController::class, 'userVerify'])->name('users.verify');
    Route::delete('/users/{user}', [AdminController::class, 'userDelete'])->name('users.delete');
    Route::patch('/users/{id}/restore', [AdminController::class, 'userRestore'])->name('users.restore');
    Route::get('/about', [AdminController::class, 'about'])->name('about');
    Route::patch('/about', [AdminController::class, 'aboutUpdate'])->name('about.update');
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
    Route::patch('/subscriptions/{subscription}/approve', [AdminController::class, 'subscriptionApprove'])->name('subscriptions.approve');
    Route::patch('/subscriptions/{subscription}/cancel', [AdminController::class, 'subscriptionCancel'])->name('subscriptions.cancel');
    Route::get('/posts', [PostAdminController::class, 'index'])->name('posts');
    Route::patch('/posts/{post}/toggle', [PostAdminController::class, 'toggle'])->name('posts.toggle');
    Route::delete('/posts/{post}', [PostAdminController::class, 'destroy'])->name('posts.delete');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::patch('/reports/{report}/dismiss', [AdminController::class, 'reportDismiss'])->name('reports.dismiss');
    Route::patch('/reports/{report}/remove-post', [AdminController::class, 'reportRemovePost'])->name('reports.remove-post');
});

// Owner
Route::prefix('owner')->name('owner.')->middleware(['auth', 'owner'])->group(function () {
    Route::get('/', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/staff', [OwnerController::class, 'staff'])->name('staff');
    Route::post('/staff/promote', [OwnerController::class, 'staffPromote'])->name('staff.promote');
    Route::patch('/staff/{user}/revoke', [OwnerController::class, 'staffRevoke'])->name('staff.revoke');
    Route::get('/settings', [OwnerController::class, 'settings'])->name('settings');
    Route::patch('/settings', [OwnerController::class, 'settingsUpdate'])->name('settings.update');
    Route::get('/logs', [OwnerController::class, 'logs'])->name('logs');
});

require __DIR__.'/auth.php';
