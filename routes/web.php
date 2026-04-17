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
Route::get('/forum', [\App\Http\Controllers\ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/create', [\App\Http\Controllers\ForumController::class, 'create'])->name('forum.create')->middleware('auth');
Route::post('/forum', [\App\Http\Controllers\ForumController::class, 'store'])->name('forum.store')->middleware('auth');
Route::get('/forum/{thread}', [\App\Http\Controllers\ForumController::class, 'show'])->name('forum.show');
Route::post('/forum/{thread}/reply', [\App\Http\Controllers\ForumController::class, 'reply'])->name('forum.reply')->middleware('auth');
Route::delete('/forum/threads/{thread}', [\App\Http\Controllers\ForumController::class, 'destroy'])->name('forum.thread.destroy')->middleware('auth');
Route::delete('/forum/replies/{reply}', [\App\Http\Controllers\ForumController::class, 'destroyReply'])->name('forum.reply.destroy')->middleware('auth');

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
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
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

    // Fix: remplace le post Laravel en double
    Route::get('/fix-duplicate-laravel', function () {
        $post = \App\Models\Post::where('title', 'Comment structurer son premier projet Laravel solo sans se perdre')->first();
        if (! $post) return response('<pre style="background:#111;color:#f88;padding:20px">Post introuvable ou déjà corrigé.</pre>');

        $post->update([
            'title' => 'Git et le versioning : les bonnes pratiques que tout dev africain devrait adopter',
            'body'  => <<<HTML
<h2>Pourquoi Git est non-négociable</h2>
<p>Git n'est pas juste un outil de sauvegarde — c'est le langage commun de toute équipe de développement professionnelle. Pourtant, beaucoup de développeurs autodidactes africains passent des années à coder sans vraiment maîtriser Git, travaillant en solo sur des projets qui deviennent impossibles à maintenir dès qu'une deuxième personne rejoint.</p>
<h2>Les erreurs les plus fréquentes</h2>
<p>Le commit fourre-tout : <code>git commit -m "modifications"</code> qui regroupe 15 changements sans rapport. L'absence de branches — tout sur <code>main</code>, ce qui rend chaque bug correction un cauchemar. Et le <code>git push --force</code> en équipe, qui efface le travail des autres.</p>
<h2>Un workflow simple qui fonctionne</h2>
<p>Pour un projet solo ou une petite équipe, ce flow couvre 90% des besoins :</p>
<ul>
<li><strong>main</strong> — uniquement du code stable et deployé</li>
<li><strong>develop</strong> — branche d'intégration, toujours potentiellement deployable</li>
<li><strong>feature/nom-feature</strong> — une branche par fonctionnalité</li>
</ul>
<p>Chaque commit doit répondre à la question : <em>"Qu'est-ce que ce commit fait ?"</em> en une phrase. <code>feat: ajouter l'authentification Google</code> vaut mieux que <code>update</code>.</p>
<h2>Les conventions de commit (Conventional Commits)</h2>
<p>Le standard <strong>Conventional Commits</strong> est adopté par la majorité des projets open source sérieux. La syntaxe : <code>type(scope): description</code>. Types principaux : <code>feat</code>, <code>fix</code>, <code>docs</code>, <code>refactor</code>, <code>test</code>, <code>chore</code>. Ça paraît formel mais ça devient instinctif en 2 semaines.</p>
<h2>GitHub comme portfolio</h2>
<p>Pour un développeur africain qui cherche à travailler avec des clients internationaux, un profil GitHub actif avec des commits propres et réguliers est souvent plus convaincant qu'un CV. Le code parle.</p>
HTML,
            'category' => 'dev',
        ]);

        return response('<pre style="background:#111;color:#8f8;padding:20px">✅ Post mis à jour. <a href="/blog" style="color:#C8522A">Voir le blog</a></pre>');
    })->name('fix-duplicate-laravel');

    // Seeder de contenu (one-shot)
    Route::get('/seed-content', function () {
        try {
            $seeder = new \Database\Seeders\ContentSeeder();
            $seeder->run();
            return response('<pre style="background:#111;color:#8f8;padding:20px;font-family:monospace">✅ Contenu généré avec succès !'
                . "\n\n<a href='/blog' style='color:#C8522A'>→ Voir le blog</a>   <a href='/forum' style='color:#C8522A'>→ Voir le forum</a></pre>");
        } catch (\Throwable $e) {
            return response('<pre style="background:#111;color:#f88;padding:20px;font-family:monospace">❌ Erreur : ' . e($e->getMessage()) . "\n\n" . e($e->getTraceAsString()) . '</pre>', 500);
        }
    })->name('seed-content');
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
