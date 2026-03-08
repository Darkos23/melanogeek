<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CreatorRequest;
use App\Models\Post;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ── Dashboard ──────────────────────────────
    public function dashboard()
    {
        $stats = [
            'users_total'     => User::count(),
            'users_active'    => User::where('is_active', true)->count(),
            'users_new'       => User::whereDate('created_at', '>=', now()->subDays(7))->count(),
            'posts_total'     => Post::count(),
            'posts_published' => Post::where('is_published', true)->count(),
            'subs_active'     => Subscription::where('status', 'active')->count(),
            'revenue_xof'     => Subscription::where('status', 'active')
                                    ->where('currency', 'XOF')->sum('amount'),
            'creator_requests_pending' => CreatorRequest::where('status', 'pending')->count(),
        ];

        $recent_users = User::latest()->limit(8)->get();
        $recent_posts = Post::with('user')->latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_posts'));
    }

    // ── Utilisateurs ───────────────────────────
    public function users(Request $request)
    {
        $query = User::withTrashed();

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->role) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function userEdit(User $user)
    {
        // Un admin ne peut pas éditer un autre admin ou un owner
        if (! auth()->user()->isOwner() && $user->isStaff()) {
            abort(403, 'Vous ne pouvez pas modifier un membre du staff.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function userUpdate(Request $request, User $user)
    {
        $currentUser = auth()->user();

        // Un admin ne peut pas modifier un autre admin/owner
        if (! $currentUser->isOwner() && $user->isStaff()) {
            abort(403, 'Action réservée au propriétaire.');
        }

        // Les rôles disponibles selon le niveau de l'utilisateur courant
        $allowedRoles = $currentUser->isOwner()
            ? ['user', 'creator', 'admin', 'owner']
            : ['user', 'creator'];

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email,' . $user->id],
            'role'         => ['required', 'in:' . implode(',', $allowedRoles)],
            'plan'         => ['nullable', 'string'],
            'is_verified'  => ['boolean'],
            'is_active'    => ['boolean'],
            'country_type' => ['nullable', 'string'],
        ]);

        // ── Unicité du rôle owner ─────────────────────────────────
        // Empêche qu'un 2ème owner soit créé (sauf si on modifie le owner actuel lui-même)
        if ($data['role'] === 'owner' && $user->role !== 'owner') {
            $existingOwner = \App\Models\User::where('role', 'owner')->exists();
            if ($existingOwner) {
                return back()
                    ->withInput()
                    ->withErrors(['role' => 'Il ne peut y avoir qu\'un seul propriétaire (owner). Rétrograder l\'actuel avant d\'en nommer un nouveau.']);
            }
        }

        $data['is_verified'] = $request->boolean('is_verified');
        $data['is_active']   = $request->boolean('is_active');
        $data['plan']        = $data['plan'] ?: 'free';

        $user->update($data);

        ActivityLog::record('user.update', "Mise à jour du profil de @{$user->username}", 'User', $user->id);

        return back()->with('success', 'Utilisateur mis à jour.');
    }

    public function userToggle(User $user)
    {
        // Un admin ne peut pas désactiver un autre admin/owner
        if (! auth()->user()->isOwner() && $user->isStaff()) {
            abort(403, 'Action réservée au propriétaire.');
        }

        $user->update(['is_active' => ! $user->is_active]);
        $label = $user->is_active ? 'activé' : 'suspendu';
        ActivityLog::record('user.toggle', "Compte de @{$user->username} {$label}", 'User', $user->id);
        return back()->with('success', "Compte {$label}.");
    }

    public function userVerify(User $user)
    {
        $user->update(['is_verified' => ! $user->is_verified]);
        $label = $user->is_verified ? 'vérifié' : 'non vérifié';
        ActivityLog::record('user.verify', "Badge de @{$user->username} : {$label}", 'User', $user->id);
        return back()->with('success', "Badge {$label}.");
    }

    public function userDelete(User $user)
    {
        // Un admin ne peut pas supprimer un autre admin/owner
        if (! auth()->user()->isOwner() && $user->isStaff()) {
            abort(403, 'Action réservée au propriétaire.');
        }

        ActivityLog::record('user.delete', "Suppression du compte @{$user->username}", 'User', $user->id);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Compte supprimé.');
    }

    public function userRestore(int $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        ActivityLog::record('user.restore', "Compte @{$user->username} restauré", 'User', $user->id);
        return back()->with('success', 'Compte restauré.');
    }

    // ── Publications ───────────────────────────
    public function posts(Request $request)
    {
        $query = Post::with('user')->withTrashed();

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if ($request->has('published')) {
            $query->where('is_published', $request->boolean('published'));
        }

        $posts = $query->latest()->paginate(25)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function postToggle(Post $post)
    {
        $post->update(['is_published' => ! $post->is_published]);
        $label = $post->is_published ? 'publiée' : 'masquée';
        ActivityLog::record('post.toggle', "Publication #{$post->id} {$label}", 'Post', $post->id);
        return back()->with('success', "Publication {$label}.");
    }

    public function postDelete(Post $post)
    {
        ActivityLog::record('post.delete', "Suppression de la publication #{$post->id}", 'Post', $post->id);
        $post->delete();
        return back()->with('success', 'Publication supprimée.');
    }

    // ── Abonnements ────────────────────────────
    public function subscriptions(Request $request)
    {
        $query = Subscription::with('user');

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $subscriptions = $query->latest()->paginate(25)->withQueryString();

        $revenue = [
            'xof'     => Subscription::where('status', 'active')->where('currency', 'XOF')->sum('amount'),
            'eur'     => Subscription::where('status', 'active')->where('currency', 'EUR')->sum('amount'),
            'total'   => Subscription::where('status', 'active')->count(),
            'pending' => Subscription::where('status', 'pending')->count(),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'revenue'));
    }

    // ── Demandes creator ───────────────────────
    public function creatorRequests(Request $request)
    {
        $query = CreatorRequest::with(['user', 'reviewer']);

        if ($status = $request->status) {
            $query->where('status', $status);
        } else {
            $query->where('status', 'pending');
        }

        $requests = $query->latest()->paginate(20)->withQueryString();

        return view('admin.creator-requests.index', compact('requests'));
    }

    public function creatorRequestApprove(CreatorRequest $creatorRequest)
    {
        $creatorRequest->update([
            'status'      => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $creatorRequest->user->update(['role' => 'creator', 'is_active' => true]);

        ActivityLog::record('creator.approve', "Demande creator approuvée pour @{$creatorRequest->user->username}", 'User', $creatorRequest->user_id);

        return back()->with('success', "{$creatorRequest->user->name} est maintenant créateur.");
    }

    public function creatorRequestReject(CreatorRequest $creatorRequest)
    {
        $creatorRequest->update([
            'status'      => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        ActivityLog::record('creator.reject', "Demande creator refusée pour @{$creatorRequest->user->username}", 'User', $creatorRequest->user_id);

        return back()->with('success', "Demande de {$creatorRequest->user->name} refusée.");
    }

    // ── Page À propos ──────────────────────────
    public function about()
    {
        $fields = ['about_title', 'about_tagline', 'about_mission', 'about_story', 'about_values'];
        $settings = [];
        foreach ($fields as $key) {
            $settings[$key] = Setting::get($key, '');
        }

        return view('admin.about', compact('settings'));
    }

    public function aboutUpdate(Request $request)
    {
        $request->validate([
            'about_title'    => 'nullable|string|max:120',
            'about_tagline'  => 'nullable|string|max:240',
            'about_mission'  => 'nullable|string|max:2000',
            'about_story'    => 'nullable|string|max:5000',
            'about_values'   => 'nullable|string|max:3000',
        ]);

        $fields = ['about_title', 'about_tagline', 'about_mission', 'about_story', 'about_values'];
        foreach ($fields as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        ActivityLog::record('settings.about', 'Page À propos mise à jour', null, null);

        return back()->with('success', 'Page À propos mise à jour.');
    }

    // ── Signalements ────────────────────────────
    public function reports(Request $request)
    {
        $query = Report::with(['reporter', 'post.user'])->latest();

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $reports      = $query->paginate(30)->withQueryString();
        $pendingCount = Report::pending()->count();

        return view('admin.reports.index', compact('reports', 'pendingCount'));
    }

    public function subscriptionApprove(Subscription $subscription)
    {
        $subscription->update([
            'status'     => 'active',
            'expires_at' => now()->addDays(30),
        ]);

        $subscription->user->update([
            'plan'            => $subscription->plan,
            'plan_expires_at' => now()->addDays(30),
        ]);

        ActivityLog::record('subscription.approve', "Abonnement #{$subscription->id} approuvé (plan {$subscription->plan})", 'Subscription', $subscription->id);
        return back()->with('success', 'Abonnement activé — plan utilisateur mis à jour.');
    }

    public function subscriptionCancel(Subscription $subscription)
    {
        $subscription->update(['status' => 'cancelled']);
        ActivityLog::record('subscription.cancel', "Abonnement #{$subscription->id} annulé", 'Subscription', $subscription->id);
        return back()->with('success', 'Abonnement annulé.');
    }

    // ── Candidatures créateurs ──────────────────
    public function applications(Request $request)
    {
        $currentStatus = $request->get('status', 'pending');

        // Scope : uniquement les candidatures créateur
        // role=null  → créateur en attente / refusé
        // role=creator → créateur approuvé
        // NULL NOT IN (...) = NULL en SQL, donc on cible explicitement les bons rôles
        $creatorsOnly = fn($q) => $q->where(function ($q) {
            $q->whereNull('role')->orWhere('role', 'creator');
        });

        $pending = User::where('status', $currentStatus)
            ->tap($creatorsOnly)
            ->latest()
            ->paginate(20);

        return view('admin.applications.index', [
            'pending'       => $pending,
            'currentStatus' => $currentStatus,
            'approved'      => User::where('status', 'approved')->tap($creatorsOnly)->count(),
            'rejected'      => User::where('status', 'rejected')->tap($creatorsOnly)->count(),
        ]);
    }

    public function applicationApprove(User $user)
    {
        $user->update([
            'status'      => 'approved',
            'role'        => 'creator',
            'is_active'   => true,
            'approved_at' => now(),
        ]);

        ActivityLog::record('application.approve', "Candidature de @{$user->username} approuvée", 'User', $user->id);

        return back()->with('success', "Candidature de {$user->name} approuvée ✅");
    }

    public function applicationReject(Request $request, User $user)
    {
        $user->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        ActivityLog::record('application.reject', "Candidature de @{$user->username} refusée", 'User', $user->id);

        return back()->with('success', "Candidature de {$user->name} refusée.");
    }

    public function reportDismiss(Report $report)
    {
        $report->update(['status' => 'dismissed']);
        ActivityLog::record('report.dismiss', "Signalement #{$report->id} ignoré", null, null);
        return back()->with('success', 'Signalement ignoré.');
    }

    public function reportRemovePost(Report $report)
    {
        $post = $report->post;
        if ($post) {
            Report::where('post_id', $post->id)->update(['status' => 'reviewed']);
            $post->delete();
            ActivityLog::record('report.remove', "Post #{$post->id} supprimé suite signalement #{$report->id}", null, null);
        }
        return back()->with('success', 'Publication supprimée et signalements résolus.');
    }
}
