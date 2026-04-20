<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reflection;
use App\Models\Activity;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers       = User::count();
        $totalReflections = Reflection::count();
        $totalActivities  = Activity::count();
        $totalGoals       = Goal::count();
        $recentUsers      = User::latest()->take(5)->get();
        $avgMood          = Reflection::avg('mood');

        return view('admin.index', compact(
            'totalUsers', 'totalReflections', 'totalActivities',
            'totalGoals', 'recentUsers', 'avgMood'
        ));
    }

    public function users(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function userShow(User $user)
    {
        $reflections = Reflection::where('user_id', $user->id)->latest()->take(5)->get();
        $activities  = Activity::where('user_id', $user->id)->latest()->take(5)->get();
        $goals       = Goal::where('user_id', $user->id)->get();
        $avgMood     = Reflection::where('user_id', $user->id)->avg('mood');

        return view('admin.user-show', compact('user', 'reflections', 'activities', 'goals', 'avgMood'));
    }

    public function userUpdate(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,suspended',
        ]);

        $before = $user->status ?? 'active';
        $user->status = $request->status;
        $user->save();

        // Admin action log
        \DB::table('admin_logs')->insert([
            'admin_id'   => Auth::id(),
            'action'     => 'user_status_update',
            'target_id'  => $user->id,
            'before'     => $before,
            'after'      => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', '✅ User status updated!');
    }

    public function content(Request $request)
    {
        $reflections = Reflection::with('user')
                        ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
                        ->when($request->date, fn($q) => $q->whereDate('created_at', $request->date))
                        ->latest()->paginate(20);

        $activities = Activity::with('user')
                        ->when($request->type, fn($q) => $q->where('type', $request->type))
                        ->latest()->paginate(20);

        return view('admin.content', compact('reflections', 'activities'));
    }

    public function logs()
    {
        $logs = \DB::table('admin_logs')
                   ->join('users as admins', 'admin_logs.admin_id', '=', 'admins.id')
                   ->join('users as targets', 'admin_logs.target_id', '=', 'targets.id')
                   ->select('admin_logs.*', 'admins.name as admin_name', 'targets.name as target_name')
                   ->latest('admin_logs.created_at')
                   ->paginate(20);

        return view('admin.logs', compact('logs'));
    }
}
