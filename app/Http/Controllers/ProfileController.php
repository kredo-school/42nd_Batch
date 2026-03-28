<?php
namespace App\Http\Controllers;

use App\Models\Reflection;
use App\Models\Activity;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user    = Auth::user();

        // 統計データ
        $totalDays   = Reflection::where('user_id', $user->id)->count();
        $avgMood     = Reflection::where('user_id', $user->id)->avg('mood');
        $totalGoals  = Goal::where('user_id', $user->id)->count();
        $totalActs   = Activity::where('user_id', $user->id)->count();

        // 今月のGoal
        $monthlyGoals = Goal::where('user_id', $user->id)->latest()->take(5)->get();

        // 最近のActivity（3件）
        $recentActivities = Activity::where('user_id', $user->id)->latest()->take(3)->get();

        // 最近のReflection（3件）
        $recentReflections = Reflection::where('user_id', $user->id)->latest()->take(3)->get();

        // Mood分布
        $moodCounts = Reflection::where('user_id', $user->id)
                        ->selectRaw('mood, count(*) as count')
                        ->groupBy('mood')
                        ->pluck('count', 'mood')
                        ->toArray();

        return view('profile.index', compact(
            'user', 'totalDays', 'avgMood', 'totalGoals', 'totalActs',
            'monthlyGoals', 'recentActivities', 'recentReflections', 'moodCounts'
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
