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
        return view('activity.create');
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
