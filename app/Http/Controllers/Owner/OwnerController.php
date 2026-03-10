<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CreatorRequest;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    // ── Dashboard owner ────────────────────────────────────────
    public function dashboard()
    {
        // Stats générales
        $stats = [
            'users_total'    => User::count(),
            'users_active'   => User::where('is_active', true)->count(),
            'users_new_week' => User::whereDate('created_at', '>=', now()->subDays(7))->count(),
            'creators_total' => User::where('role', 'creator')->count(),
            'admins_total'   => User::where('role', 'admin')->count(),
            'posts_total'    => Post::count(),
            'subs_active'    => Subscription::where('status', 'active')->count(),
            'requests_pending' => CreatorRequest::where('status', 'pending')->count(),

            // Finances
            'revenue_xof'    => Subscription::where('status', 'active')->where('currency', 'XOF')->sum('amount'),
            'revenue_eur'    => Subscription::where('status', 'active')->where('currency', 'EUR')->sum('amount'),
            'revenue_month'  => Subscription::where('status', 'active')
                                    ->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->sum('amount'),

            // Par plan
            'subs_african'   => Subscription::where('status', 'active')->where('plan', 'african')->count(),
            'subs_global'    => Subscription::where('status', 'active')->where('plan', 'global')->count(),
        ];

        $recent_logs = ActivityLog::with('staff')->latest('created_at')->limit(10)->get();

        return view('owner.dashboard', compact('stats', 'recent_logs'));
    }

    // ── Finances ───────────────────────────────────────────────
    public function finances()
    {
        // Revenus par mois (6 derniers mois)
        $monthly = Subscription::where('status', 'active')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('currency'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month', 'currency')
            ->orderBy('year')->orderBy('month')
            ->get();

        // Revenus par plan
        $byPlan = Subscription::where('status', 'active')
            ->select('plan', 'currency', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('plan', 'currency')
            ->get();

        // Par méthode de paiement
        $byMethod = Subscription::where('status', 'active')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        $totals = [
            'xof'     => Subscription::where('status', 'active')->where('currency', 'XOF')->sum('amount'),
            'eur'     => Subscription::where('status', 'active')->where('currency', 'EUR')->sum('amount'),
            'active'  => Subscription::where('status', 'active')->count(),
            'expired' => Subscription::where('status', 'expired')->count(),
            'pending' => Subscription::where('status', 'pending')->count(),
        ];

        return view('owner.finances', compact('monthly', 'byPlan', 'byMethod', 'totals'));
    }

    // ── Staff (gestion du staff) ────────────────────────────────
    public function staff()
    {
        $admins = User::where('role', 'admin')->latest()->get();
        $cms    = User::where('role', 'cm')->latest()->get();
        $users  = User::whereIn('role', ['user', 'creator'])
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
