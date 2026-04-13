<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Post;
use App\Models\Report;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    // ── Dashboard owner ────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'users_total'    => User::count(),
            'users_active'   => User::where('is_active', true)->count(),
            'users_new_week' => User::whereDate('created_at', '>=', now()->subDays(7))->count(),
            'users_today'    => User::whereDate('created_at', today())->count(),
            'admins_total'   => User::where('role', 'admin')->count(),
            'posts_total'    => Post::count(),
        ];

        $recent_logs    = ActivityLog::with('staff')->latest()->limit(8)->get();
        $pending_reports = Report::with(['reporter', 'post'])->where('status', 'pending')->latest()->limit(8)->get();
        $latest_posts   = Post::with('user')->latest()->limit(8)->get();

        return view('owner.dashboard', compact('stats', 'recent_logs', 'pending_reports', 'latest_posts'));
    }

    // ── Staff (gestion du staff) ────────────────────────────────
    public function staff()
    {
        $admins = User::where('role', 'admin')->latest()->get();
        $cms    = User::where('role', 'cm')->latest()->get();
        $users  = User::where('role', 'user')
                      ->orderBy('name')
                      ->limit(100)
                      ->get();

        return view('owner.staff', compact('admins', 'cms', 'users'));
    }

    public function staffPromote(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role'    => ['required', 'in:admin,cm'],
        ]);
        $user = User::findOrFail($request->user_id);

        abort_if($user->isOwner(), 403);

        $role = $request->role;
        $user->update(['role' => $role]);

        $label = $role === 'admin' ? 'admin' : 'Community Manager';
        ActivityLog::record('staff.promote', "Promotion de @{$user->username} en {$label}", 'User', $user->id);

        return back()->with('success', "{$user->name} est maintenant {$label}.");
    }

    public function staffRevoke(User $user)
    {
        abort_if($user->isOwner(), 403);

        $oldRole = $user->role;
        $user->update(['role' => 'user']);

        ActivityLog::record('staff.revoke', "Révocation de @{$user->username} ({$oldRole} → user)", 'User', $user->id);

        return back()->with('success', "{$user->name} n'est plus " . ($oldRole === 'cm' ? 'CM' : 'admin') . ".");
    }

    // ── Paramètres ─────────────────────────────────────────────
    public function settings()
    {
        $settings = [
            'maintenance_mode'      => Setting::get('maintenance_mode', '0'),
            'site_announcement'     => Setting::get('site_announcement'),
            'site_announcement_type'=> Setting::get('site_announcement_type', 'info'),
            'allow_registrations'   => Setting::get('allow_registrations', '1'),
        ];

        return view('owner.settings', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'site_announcement'      => ['nullable', 'string', 'max:500'],
            'site_announcement_type' => ['required', 'in:info,warning,success'],
        ]);

        Setting::set('maintenance_mode',       $request->boolean('maintenance_mode') ? '1' : '0');
        Setting::set('allow_registrations',    $request->boolean('allow_registrations') ? '1' : '0');
        Setting::set('site_announcement',      $request->site_announcement);
        Setting::set('site_announcement_type', $request->site_announcement_type);

        ActivityLog::record('settings.update', 'Mise à jour des paramètres plateforme');

        return back()->with('success', 'Paramètres sauvegardés.');
    }

    // ── Logs d'activité ────────────────────────────────────────
    public function logs(Request $request)
    {
        $query = ActivityLog::with('staff');

        if ($action = $request->action) {
            $query->where('action', 'like', "{$action}%");
        }

        if ($staffId = $request->staff) {
            $query->where('user_id', $staffId);
        }

        $logs  = $query->latest('created_at')->paginate(30)->withQueryString();
        $staff = User::whereIn('role', ['admin', 'owner'])->orderBy('name')->get();

        return view('owner.logs', compact('logs', 'staff'));
    }
}
