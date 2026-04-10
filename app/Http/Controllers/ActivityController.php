<?php
namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Goal;
use App\Models\Reflection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function create()
{
    $user      = Auth::user();
    $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
    $weekMoods = [];

    for ($i = 0; $i < 7; $i++) {
        $date = $weekStart->copy()->addDays($i);
        $ref  = Reflection::where('user_id', $user->id)
                    ->whereDate('created_at', $date)->latest()->first();
        $weekMoods[] = [
            'label' => $date->format('D'),
            'mood'  => $ref ? $ref->mood : null,
            'today' => $date->isToday(),
        ];
    }

    $totalReflections = Reflection::where('user_id', $user->id)->count();

    $moodCounts = Reflection::where('user_id', $user->id)
                    ->selectRaw('mood, count(*) as count')
                    ->groupBy('mood')
                    ->pluck('count', 'mood')
                    ->toArray();

    return view('activity.create', compact('weekMoods', 'totalReflections', 'moodCounts'));
}

    public function store(Request $request)
    {
        $request->validate([
            'type'     => 'required|string',
            'duration' => 'required|integer|min:1',
            'note'     => 'nullable|string|max:500',
        ]);

        Activity::create([
            'user_id'   => Auth::id(),
            'type'      => $request->type,
            'duration'  => $request->duration,
            'amount'    => $request->amount,
            'intensity' => $request->intensity ?: null,
            'note'      => $request->note,
        ]);

        // Goal と Activity のカテゴリマッピング
        $categoryMap = [
            'running'  => 'exercise',
            'walking'  => 'exercise',
            'strength' => 'exercise',
            'yoga'     => 'exercise',
            'reading'  => 'reading',
            'study'    => 'study',
            'other'    => 'other',
        ];

        $goalCategory = $categoryMap[$request->type] ?? null;

        if ($goalCategory) {
            $goal = Goal::where('user_id', Auth::id())
                        ->where('category', $goalCategory)
                        ->first();

            if ($goal && $goal->current < $goal->target) {
                $goal->increment('current');
            }
        }

        return redirect()->route('dashboard')
               ->with('success', '🏃 Activity logged! Your goal progress has been updated.');
    }
}
