<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\InstructorController;
use Illuminate\Support\Facades\Route;

// ── Streaming vidéo (authentifié + inscrit) ──
Route::get('/video/{lesson}', function (\App\Models\Lesson $lesson) {
    $user = auth()->user();
    abort_unless($lesson->is_preview || $user?->isEnrolledIn($lesson->section->course), 403);

    $path = storage_path('app/' . $lesson->video_path);
    abort_unless(file_exists($path), 404);

    return response()->file($path, [
        'Content-Type'  => 'video/mp4',
        'Accept-Ranges' => 'bytes',
    ]);
})->middleware('auth')->name('lesson.video');

// ── Pages publiques ──
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/offline', fn() => view('pages.offline'))->name('offline');

Route::get('/a-propos', function () {
    $settings = [
        'about_title'   => \App\Models\Setting::get('about_title',   'À propos de Waxtu'),
        'about_tagline' => \App\Models\Setting::get('about_tagline', ''),
        'about_mission' => \App\Models\Setting::get('about_mission', ''),
        'about_story'   => \App\Models\Setting::get('about_story',   ''),
        'about_values'  => \App\Models\Setting::get('about_values',  ''),
    ];
    return view('pages.about', compact('settings'));
})->name('about');

// ── Catalogue de cours (public) ──
Route::get('/cours', [CourseController::class, 'index'])->name('courses.index');
Route::get('/cours/{course:slug}', [CourseController::class, 'show'])->name('courses.show');

// ── Profils publics ──
Route::get('/@{user:username}', [ProfileController::class, 'show'])->name('profile.show');

// ── Abonnements (page tarifs) ──
Route::get('/pricing', [SubscriptionController::class, 'pricing'])->name('subscription.pricing');

// ── Routes authentifiées ──
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('courses.index');
    })->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Messages
    Route::get('/messages', [MessagesController::class, 'index'])->name('messages.index');
    Route::get('/messages/search-users', [MessagesController::class, 'searchUsers'])->name('messages.search-users');
    Route::get('/messages/unread-count', [MessagesController::class, 'unreadCount'])->name('messages.unread-count');
    Route::get('/messages/{user:username}', [MessagesController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user:username}', [MessagesController::class, 'store'])->name('messages.store');
    Route::get('/messages/{user:username}/poll', [MessagesController::class, 'poll'])->name('messages.poll');

    // Notifications
    Route::get('/notifications',              [NotificationsController::class, 'index'])->name('notifications');
    Route::get('/notifications/dropdown',     [NotificationsController::class, 'dropdown'])->name('notifications.dropdown');
    Route::get('/notifications/unread-count', [NotificationsController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/read-all',    [NotificationsController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read',   [NotificationsController::class, 'markRead'])->name('notifications.read');

    // Push subscriptions
    Route::post('/push/subscribe',   [PushSubscriptionController::class, 'store'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');

    // Analytics (étudiant + instructeur)
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    // Abonnements paiement
    Route::get('/checkout/{plan}',      [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::post('/checkout/{plan}',     [SubscriptionController::class, 'store'])->name('subscription.store');
    Route::delete('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // ── E-learning (étudiant) ──
    Route::get('/mes-cours', function () {
        $enrollments = auth()->user()->enrollments()->with('course.instructor')->where('status', 'active')->latest()->get();
        return view('courses.my', compact('enrollments'));
    })->name('courses.my');

    Route::get('/cours/{course:slug}/inscription', [EnrollmentController::class, 'checkout'])->name('enrollments.checkout');
    Route::post('/cours/{course:slug}/inscription', [EnrollmentController::class, 'store'])->name('enrollments.store');

    Route::get('/cours/{course:slug}/lecon/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/cours/{course:slug}/lecon/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');

    Route::get('/certificats/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificats/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');

    // ── Espace instructeur ──
    Route::prefix('instructeur')->name('instructor.')->group(function () {
        Route::get('/',                              [InstructorController::class, 'dashboard'])->name('dashboard');
        Route::get('/cours/creer',                   [InstructorController::class, 'createCourse'])->name('courses.create');
        Route::post('/cours',                        [InstructorController::class, 'storeCourse'])->name('courses.store');
        Route::get('/cours/{course}/modifier',       [InstructorController::class, 'editCourse'])->name('courses.edit');
        Route::put('/cours/{course}',                [InstructorController::class, 'updateCourse'])->name('courses.update');

        Route::post('/cours/{course}/sections',      [InstructorController::class, 'storeSection'])->name('sections.store');
        Route::put('/sections/{section}',            [InstructorController::class, 'updateSection'])->name('sections.update');
        Route::delete('/sections/{section}',         [InstructorController::class, 'destroySection'])->name('sections.destroy');

        Route::post('/sections/{section}/lecons',    [InstructorController::class, 'storeLesson'])->name('lessons.store');
        Route::delete('/lecons/{lesson}',            [InstructorController::class, 'destroyLesson'])->name('lessons.destroy');
    });
});

// ── Admin ──
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/',                          [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/users',                     [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit',         [AdminController::class, 'userEdit'])->name('users.edit');
    Route::patch('/users/{user}',            [AdminController::class, 'userUpdate'])->name('users.update');
    Route::patch('/users/{user}/toggle',     [AdminController::class, 'userToggle'])->name('users.toggle');
    Route::patch('/users/{user}/verify',     [AdminController::class, 'userVerify'])->name('users.verify');
    Route::delete('/users/{user}',           [AdminController::class, 'userDelete'])->name('users.delete');
    Route::patch('/users/{id}/restore',      [AdminController::class, 'userRestore'])->name('users.restore');

    Route::get('/subscriptions',                              [AdminController::class, 'subscriptions'])->name('subscriptions');
    Route::patch('/subscriptions/{subscription}/approve',     [AdminController::class, 'subscriptionApprove'])->name('subscriptions.approve');
    Route::patch('/subscriptions/{subscription}/cancel',      [AdminController::class, 'subscriptionCancel'])->name('subscriptions.cancel');

    Route::get('/about',  [AdminController::class, 'about'])->name('about');
    Route::patch('/about', [AdminController::class, 'aboutUpdate'])->name('about.update');
});

// ── Owner ──
Route::prefix('owner')->name('owner.')->middleware(['auth', 'owner'])->group(function () {
    Route::get('/',                      [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/finances',              [OwnerController::class, 'finances'])->name('finances');
    Route::get('/staff',                 [OwnerController::class, 'staff'])->name('staff');
    Route::post('/staff/promote',        [OwnerController::class, 'staffPromote'])->name('staff.promote');
    Route::patch('/staff/{user}/revoke', [OwnerController::class, 'staffRevoke'])->name('staff.revoke');
    Route::get('/settings',              [OwnerController::class, 'settings'])->name('settings');
    Route::patch('/settings',            [OwnerController::class, 'settingsUpdate'])->name('settings.update');
    Route::get('/logs',                  [OwnerController::class, 'logs'])->name('logs');
});

require __DIR__.'/auth.php';
