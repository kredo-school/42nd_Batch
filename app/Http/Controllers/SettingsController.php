<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if ($request->section === 'notifications') {
            $user->notif_reflection = $request->has('notif_reflection');
            $user->notif_activity   = $request->has('notif_activity');
            $user->notif_goal       = $request->has('notif_goal');
            $user->notif_streak     = $request->has('notif_streak');
            $user->save();
            return redirect()->route('settings', ['tab' => 'notifications'])
                   ->with('success', '🔔 Notification settings saved!');
        }

        if ($request->section === 'privacy') {
            $user->privacy_profile_visible = $request->has('privacy_profile_visible');
            $user->privacy_data_analytics  = $request->has('privacy_data_analytics');
            $user->privacy_two_factor      = $request->has('privacy_two_factor');
            $user->save();
            return redirect()->route('settings', ['tab' => 'privacy'])
                   ->with('success', '🔒 Privacy settings saved!');
        }

        return redirect()->route('settings')->with('success', '✅ Settings saved!');
    }
}
