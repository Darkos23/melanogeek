<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CreatorRequestController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\CM\CMController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoryController;
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

Route::get('/explore', [ExploreController::class, 'index'])->name('explore');

// ── Abonnements (page tarifs publique) ──
Route::get('/pricing', [SubscriptionController::class, 'pricing'])->name('subscription.pricing');

// ── Marketplace (public) ──
Route::get('/marketplace',          [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/{service}', [MarketplaceController::class, 'show'])->name('marketplace.show');

Route::get('/creators', [CreatorController::class, 'index'])->name('creators');
Route::get('/classement', [RankingController::class, 'index'])->name('ranking');

// ── Dashboard Breeze (garde pour l'instant) ──
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ── Profil ──
Route::get('/@{user:username}', [ProfileController::class, 'show'])->name('profile.show');

// ── Routes authentifiées (créateurs approuvés uniquement) ──
Route::middleware(['auth', 'approved'])->group(function () {

    // Feed
    Route::get('/feed', [FeedController::class, 'index'])->name('feed');
    Route::get('/feed/more', [FeedController::class, 'more'])->name('feed.more');

    // Profile edit (Breeze)
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

    // Push subscriptions (Web Push)
    Route::post('/push/subscribe',   [PushSubscriptionController::class, 'store'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    // Settings → redirige vers l'édition de profil (déjà complet)
    Route::get('/settings', function () {
        return redirect()->route('profile.edit');
    })->name('settings');

    // Posts
    Route::get('/posts/create',           [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts',                 [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}',           [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/data',      [PostController::class, 'data'])->name('posts.data');
    Route::post('/posts/{post}/like',     [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/report',   [ReportController::class, 'store'])->name('posts.report');
    Route::patch('/posts/{post}/publish', function (App\Models\Post $post) {
        abort_if(auth()->id() !== $post->user_id, 403);
        $post->update(['is_published' => true]);
        return back()->with('status', 'post-published');
    })->name('posts.publish');
    Route::delete('/posts/{post}', function (App\Models\Post $post) {
        abort_if(auth()->id() !== $post->user_id, 403);
        $post->delete();
        return redirect()->route('profile.show', auth()->user()->username)
                         ->with('success', 'Publication supprimée.');
    })->name('posts.destroy');

    // Follow / Unfollow
    Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])->name('users.follow');

    // Blocage
    Route::post('/users/{user}/block', [BlockController::class, 'toggle'])->name('users.block');

    // Disponibilité créateur
    Route::post('/availability/toggle', [AvailabilityController::class, 'toggle'])->name('availability.toggle');

    // Portfolio
    Route::get('/portfolio/create',         [PortfolioController::class, 'create'])->name('portfolio.create');
    Route::post('/portfolio',               [PortfolioController::class, 'store'])->name('portfolio.store');
    Route::get('/portfolio/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::put('/portfolio/{portfolio}',    [PortfolioController::class, 'update'])->name('portfolio.update');
    Route::delete('/portfolio/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');

    // Stories
    Route::post('/stories',                              [StoryController::class, 'store'])->name('stories.store');
    Route::delete('/stories/{story}',                    [StoryController::class, 'destroy'])->name('stories.destroy');
    Route::get('/stories/{user:username}/list',          [StoryController::class, 'userStories'])->name('stories.user');

    // Demande creator
    Route::post('/creator-request', [CreatorRequestController::class, 'store'])->name('creator-request.store');

    // Abonnements
    Route::get('/checkout/{plan}',     [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::post('/checkout/{plan}',    [SubscriptionController::class, 'store'])->name('subscription.store');
    Route::delete('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // Services (créateurs uniquement, géré dans le controller)
    Route::get('/my-services',               [ServiceController::class, 'manage'])->name('services.manage');
    Route::get('/services/create',           [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services',                 [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit',   [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}',        [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}',     [ServiceController::class, 'destroy'])->name('services.destroy');

    // Commandes
    Route::get('/my-orders',                 [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders',                   [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}',            [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/accept',     [OrderController::class, 'accept'])->name('orders.accept');
    Route::patch('/orders/{order}/start-work', [OrderController::class, 'startWork'])->name('orders.start-work');
    Route::patch('/orders/{order}/deliver',    [OrderController::class, 'deliver'])->name('orders.deliver');
    Route::patch('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::patch('/orders/{order}/cancel',   [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/review',    [ReviewController::class, 'store'])->name('orders.review');

});

// ── Candidatures (pages post-inscription, sans middleware approved) ──
Route::middleware('auth')->group(function () {
    Route::get('/application/pending',  fn() => view('application.pending'))->name('application.pending');
    Route::get('/application/rejected', fn() => view('application.rejected'))->name('application.rejected');
});

// ── Admin (Staff : admin + owner) ──
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/',                          [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users',                     [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit',         [AdminController::class, 'userEdit'])->name('users.edit');
    Route::patch('/users/{user}',            [AdminController::class, 'userUpdate'])->name('users.update');
    Route::patch('/users/{user}/toggle',     [AdminController::class, 'userToggle'])->name('users.toggle');
    Route::patch('/users/{user}/verify',     [AdminController::class, 'userVerify'])->name('users.verify');
    Route::delete('/users/{user}',           [AdminController::class, 'userDelete'])->name('users.delete');
    Route::patch('/users/{id}/restore',      [AdminController::class, 'userRestore'])->name('users.restore');

    // Posts
    Route::get('/posts',                     [AdminController::class, 'posts'])->name('posts');
    Route::patch('/posts/{post}/toggle',     [AdminController::class, 'postToggle'])->name('posts.toggle');
    Route::delete('/posts/{post}',           [AdminController::class, 'postDelete'])->name('posts.delete');

    // Abonnements
    Route::get('/subscriptions',                              [AdminController::class, 'subscriptions'])->name('subscriptions');
    Route::patch('/subscriptions/{subscription}/approve',     [AdminController::class, 'subscriptionApprove'])->name('subscriptions.approve');
    Route::patch('/subscriptions/{subscription}/cancel',      [AdminController::class, 'subscriptionCancel'])->name('subscriptions.cancel');

    // Candidatures créateurs
    Route::get('/applications',                          [AdminController::class, 'applications'])->name('applications');
    Route::patch('/applications/{user}/approve',         [AdminController::class, 'applicationApprove'])->name('applications.approve');
    Route::patch('/applications/{user}/reject',          [AdminController::class, 'applicationReject'])->name('applications.reject');

    // Signalements
    Route::get('/reports',                        [AdminController::class, 'reports'])->name('reports');
    Route::patch('/reports/{report}/dismiss',     [AdminController::class, 'reportDismiss'])->name('reports.dismiss');
    Route::patch('/reports/{report}/remove-post', [AdminController::class, 'reportRemovePost'])->name('reports.remove-post');

    // Page À propos
    Route::get('/about',  [AdminController::class, 'about'])->name('about');
    Route::patch('/about', [AdminController::class, 'aboutUpdate'])->name('about.update');
});

// ── Community Manager ──
Route::prefix('cm')->name('cm.')->middleware(['auth', 'cm'])->group(function () {
    Route::get('/',                              [CMController::class, 'dashboard'])->name('dashboard');
    Route::get('/posts',                         [CMController::class, 'posts'])->name('posts');
    Route::delete('/posts/{post}',               [CMController::class, 'deletePost'])->name('posts.delete');
    Route::post('/posts/{id}/restore',           [CMController::class, 'restorePost'])->name('posts.restore');
    Route::get('/reports',                       [CMController::class, 'reports'])->name('reports');
    Route::patch('/reports/{report}/resolve',    [CMController::class, 'resolveReport'])->name('reports.resolve');
    Route::get('/comments',                      [CMController::class, 'comments'])->name('comments');
    Route::delete('/comments/{comment}',         [CMController::class, 'deleteComment'])->name('comments.delete');
    Route::get('/homepage',                      [CMController::class, 'homepageEdit'])->name('homepage');
    Route::patch('/homepage',                    [CMController::class, 'homepageUpdate'])->name('homepage.update');
    Route::get('/about',                         [CMController::class, 'aboutEdit'])->name('about');
    Route::patch('/about',                       [CMController::class, 'aboutUpdate'])->name('about.update');
    Route::get('/niches',                        [CMController::class, 'nichesEdit'])->name('niches');
    Route::patch('/niches',                      [CMController::class, 'nichesUpdate'])->name('niches.update');
});

// ── Owner (owner uniquement) ──
Route::prefix('owner')->name('owner.')->middleware(['auth', 'owner'])->group(function () {
    Route::get('/',                   [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/finances',           [OwnerController::class, 'finances'])->name('finances');
    Route::get('/staff',              [OwnerController::class, 'staff'])->name('staff');
    Route::post('/staff/promote',     [OwnerController::class, 'staffPromote'])->name('staff.promote');
    Route::patch('/staff/{user}/revoke', [OwnerController::class, 'staffRevoke'])->name('staff.revoke');
    Route::get('/settings',           [OwnerController::class, 'settings'])->name('settings');
    Route::patch('/settings',         [OwnerController::class, 'settingsUpdate'])->name('settings.update');
    Route::get('/logs',               [OwnerController::class, 'logs'])->name('logs');
});

require __DIR__.'/auth.php';
