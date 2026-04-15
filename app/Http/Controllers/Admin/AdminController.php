<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Post;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users_total' => User::count(),
            'users_active' => User::where('is_active', true)->count(),
            'users_new' => User::whereDate('created_at', '>=', now()->subDays(7))->count(),
            'posts_total' => Post::count(),
            'posts_published' => Post::where('is_published', true)->count(),
        ];

        $recent_users = User::latest()->limit(8)->get();
        $recent_posts = Post::with('user')->latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_posts'));
    }

    public function users(Request $request)
    {
        $query = User::withTrashed();

        if ($search = $request->search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
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
        if (! auth()->user()->isOwner() && $user->isStaff()) {
            abort(403, 'Vous ne pouvez pas modifier un membre du staff.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function userUpdate(Request $request, User $user)
    {
        $currentUser = auth()->user();

        if (! $currentUser->isOwner() && $user->isStaff()) {
            abort(403, 'Action reservee au proprietaire.');
        }

        $allowedRoles = $currentUser->isOwner()
            ? ['user', 'admin', 'owner']
            : ['user'];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'is_verified' => ['boolean'],
            'is_active' => ['boolean'],
            'country_type' => ['nullable', 'string'],
        ]);

        if ($data['role'] === 'owner' && $user->role !== 'owner') {
            $existingOwner = User::where('role', 'owner')->exists();

            if ($existingOwner) {
                return back()
                    ->withInput()
                    ->withErrors(['role' => 'Il ne peut y avoir qu un seul proprietaire (owner). Retrograder l actuel avant d en nommer un nouveau.']);
            }
        }

        $data['is_verified'] = $request->boolean('is_verified');
        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        ActivityLog::record('user.update', "Mise a jour du profil de @{$user->username}", 'User', $user->id);

        return back()->with('success', 'Utilisateur mis a jour.');
    }

    public function userToggle(User $user)
    {
        if (! auth()->user()->isOwner() && $user->isStaff()) {
            abort(403, 'Action reservee au proprietaire.');
        }

        $user->update(['is_active' => ! $user->is_active]);
        $label = $user->is_active ? 'active' : 'suspendu';

        ActivityLog::record('user.toggle', "Compte de @{$user->username} {$label}", 'User', $user->id);

        return back()->with('success', "Compte {$label}.");
    }

    public function userVerify(User $user)
    {
        $user->update(['is_verified' => ! $user->is_verified]);
        $label = $user->is_verified ? 'verifie' : 'non verifie';

        ActivityLog::record('user.verify', "Badge de @{$user->username} : {$label}", 'User', $user->id);

        return back()->with('success', "Badge {$label}.");
    }

    public function userDelete(User $user)
    {
        if (! auth()->user()->isOwner() && $user->isStaff()) {
            abort(403, 'Action reservee au proprietaire.');
        }

        ActivityLog::record('user.delete', "Suppression du compte @{$user->username}", 'User', $user->id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Compte supprime.');
    }

    public function userRestore(int $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        ActivityLog::record('user.restore', "Compte @{$user->username} restaure", 'User', $user->id);

        return back()->with('success', 'Compte restaure.');
    }

    public function posts(Request $request)
    {
        $query = Post::with('user')->withTrashed();

        if ($search = $request->search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
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
        $label = $post->is_published ? 'publiee' : 'masquee';

        ActivityLog::record('post.toggle', "Publication #{$post->id} {$label}", 'Post', $post->id);

        return back()->with('success', "Publication {$label}.");
    }

    public function postDelete(Post $post)
    {
        ActivityLog::record('post.delete', "Suppression de la publication #{$post->id}", 'Post', $post->id);
        $post->delete();

        return back()->with('success', 'Publication supprimee.');
    }

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
            'about_title' => 'nullable|string|max:120',
            'about_tagline' => 'nullable|string|max:240',
            'about_mission' => 'nullable|string|max:2000',
            'about_story' => 'nullable|string|max:5000',
            'about_values' => 'nullable|string|max:3000',
        ]);

        $fields = ['about_title', 'about_tagline', 'about_mission', 'about_story', 'about_values'];

        foreach ($fields as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        ActivityLog::record('settings.about', 'Page A propos mise a jour', null, null);

        return back()->with('success', 'Page A propos mise a jour.');
    }

    public function subscriptions(Request $request)
    {
        $query = Subscription::with('user');

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $subscriptions = $query->latest()->paginate(25)->withQueryString();

        $revenue = [
            'xof' => Subscription::where('status', 'active')->where('currency', 'XOF')->sum('amount'),
            'eur' => Subscription::where('status', 'active')->where('currency', 'EUR')->sum('amount'),
            'total' => Subscription::where('status', 'active')->count(),
            'pending' => Subscription::where('status', 'pending')->count(),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'revenue'));
    }

    public function subscriptionApprove(Subscription $subscription)
    {
        $subscription->update([
            'status' => 'active',
            'expires_at' => now()->addDays(30),
        ]);

        $subscription->user->update([
            'plan' => $subscription->plan,
            'plan_expires_at' => now()->addDays(30),
        ]);

        ActivityLog::record('subscription.approve', "Abonnement #{$subscription->id} approuve", 'Subscription', $subscription->id);

        return back()->with('success', 'Abonnement active.');
    }

    public function subscriptionCancel(Subscription $subscription)
    {
        $subscription->update(['status' => 'cancelled']);

        ActivityLog::record('subscription.cancel', "Abonnement #{$subscription->id} annule", 'Subscription', $subscription->id);

        return back()->with('success', 'Abonnement annule.');
    }

    public function applications(Request $request)
    {
        $currentStatus = $request->get('status', 'pending');

        $creatorsOnly = fn ($query) => $query->where(function ($query) {
            $query->whereNull('role')->orWhere('role', 'creator');
        });

        $pending = User::where('status', $currentStatus)
            ->tap($creatorsOnly)
            ->latest()
            ->paginate(20);

        return view('admin.applications.index', [
            'pending' => $pending,
            'currentStatus' => $currentStatus,
            'approved' => User::where('status', 'approved')->tap($creatorsOnly)->count(),
            'rejected' => User::where('status', 'rejected')->tap($creatorsOnly)->count(),
        ]);
    }

    public function applicationApprove(User $user)
    {
        $user->update([
            'status' => 'approved',
            'role' => 'creator',
            'is_active' => true,
            'approved_at' => now(),
        ]);

        ActivityLog::record('application.approve', "Candidature de @{$user->username} approuvee", 'User', $user->id);

        return back()->with('success', "Candidature de {$user->name} approuvee.");
    }

    public function applicationReject(Request $request, User $user)
    {
        $user->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        ActivityLog::record('application.reject', "Candidature de @{$user->username} refusee", 'User', $user->id);

        return back()->with('success', "Candidature de {$user->name} refusee.");
    }

    public function reports(Request $request)
    {
        $query = Report::with(['reporter', 'post.user'])->latest();

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $reports = $query->paginate(30)->withQueryString();
        $pendingCount = Report::pending()->count();

        return view('admin.reports.index', compact('reports', 'pendingCount'));
    }

    public function reportDismiss(Report $report)
    {
        $report->update(['status' => 'dismissed']);
        ActivityLog::record('report.dismiss', "Signalement #{$report->id} ignore", null, null);

        return back()->with('success', 'Signalement ignore.');
    }

    public function reportRemovePost(Report $report)
    {
        $post = $report->post;

        if ($post) {
            Report::where('post_id', $post->id)->update(['status' => 'reviewed']);
            $post->delete();
            ActivityLog::record('report.remove', "Post #{$post->id} supprime suite signalement #{$report->id}", null, null);
        }

        return back()->with('success', 'Publication supprimee et signalements resolus.');
    }
}
