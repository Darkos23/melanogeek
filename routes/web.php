<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Pages publiques ──
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/offline', fn() => view('pages.offline'))->name('offline');

Route::get('/a-propos', function () {
    $settings = [
        'about_title'   => \App\Models\Setting::get('about_title',   'À propos de MelanoGeek'),
        'about_tagline' => \App\Models\Setting::get('about_tagline', ''),
        'about_mission' => \App\Models\Setting::get('about_mission', ''),
        'about_story'   => \App\Models\Setting::get('about_story',   ''),
        'about_values'  => \App\Models\Setting::get('about_values',  ''),
    ];
    return view('pages.about', compact('settings'));
})->name('about');

// ── Blog ──
Route::get('/blog', fn() => view('blog.index'))->name('blog.index');

// ── Forum ──
Route::get('/forum', fn() => view('forum.index'))->name('forum.index');

// ── Routes authentifiées ──
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', fn() => redirect()->route('blog.index'))->name('dashboard');

    // Profil (paramètres compte)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications',              [NotificationsController::class, 'index'])->name('notifications');
    Route::get('/notifications/dropdown',     [NotificationsController::class, 'dropdown'])->name('notifications.dropdown');
    Route::get('/notifications/unread-count', [NotificationsController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/read-all',    [NotificationsController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read',   [NotificationsController::class, 'markRead'])->name('notifications.read');

    // Push subscriptions
    Route::post('/push/subscribe',   [PushSubscriptionController::class, 'store'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');
});

// ── Admin ──
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/',                      [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',                 [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit',     [AdminController::class, 'userEdit'])->name('users.edit');
    Route::patch('/users/{user}',        [AdminController::class, 'userUpdate'])->name('users.update');
    Route::patch('/users/{user}/toggle', [AdminController::class, 'userToggle'])->name('users.toggle');
    Route::patch('/users/{user}/verify', [AdminController::class, 'userVerify'])->name('users.verify');
    Route::delete('/users/{user}',       [AdminController::class, 'userDelete'])->name('users.delete');
    Route::patch('/users/{id}/restore',  [AdminController::class, 'userRestore'])->name('users.restore');
    Route::get('/about',                 [AdminController::class, 'about'])->name('about');
    Route::patch('/about',               [AdminController::class, 'aboutUpdate'])->name('about.update');
});

// ── Owner ──
Route::prefix('owner')->name('owner.')->middleware(['auth', 'owner'])->group(function () {
    Route::get('/',                      [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/staff',                 [OwnerController::class, 'staff'])->name('staff');
    Route::post('/staff/promote',        [OwnerController::class, 'staffPromote'])->name('staff.promote');
    Route::patch('/staff/{user}/revoke', [OwnerController::class, 'staffRevoke'])->name('staff.revoke');
    Route::get('/settings',              [OwnerController::class, 'settings'])->name('settings');
    Route::patch('/settings',            [OwnerController::class, 'settingsUpdate'])->name('settings.update');
    Route::get('/logs',                  [OwnerController::class, 'logs'])->name('logs');
});

require __DIR__.'/auth.php';
