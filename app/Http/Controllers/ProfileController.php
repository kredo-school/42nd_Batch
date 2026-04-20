<?php
namespace App\Http\Controllers;

use App\Models\Reflection;
use App\Models\Activity;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
{
    $user = Auth::user();

    $totalDays  = Reflection::where('user_id', $user->id)->count();
    $totalActs  = Activity::where('user_id', $user->id)->count();
    $totalGoals = Goal::where('user_id', $user->id)->count();
    $avgMood    = Reflection::where('user_id', $user->id)->avg('mood');

    $moodCounts = Reflection::where('user_id', $user->id)
                    ->selectRaw('mood, count(*) as count')
                    ->groupBy('mood')
                    ->pluck('count', 'mood')
                    ->toArray();

    return view('profile.index', compact(
        'user', 'totalDays', 'totalActs', 'totalGoals', 'avgMood', 'moodCounts'
    ));
}

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            // 古いアバターを削除
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile')
               ->with('success', '✅ Profile updated successfully!');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect.']);
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();
        $user->delete();

        return redirect('/login')
               ->with('success', 'Your account has been deleted.');
    }
}
