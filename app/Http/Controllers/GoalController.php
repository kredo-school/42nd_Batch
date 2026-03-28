<?php
namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Activity;
use App\Models\Reflection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function index()
    {
        $goals = Goal::where('user_id', Auth::id())->latest()->get();
        return view('goal.index', compact('goals'));
    }

    public function create()
    {
        return view('goal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string',
            'period'   => 'required|string',
            'target'   => 'required|integer|min:1',
            'unit'     => 'nullable|string|max:50',
            'note'     => 'nullable|string|max:500',
        ]);

        // 既存の記録数を初期値として設定
        $current = $this->countExistingLogs(
            $request->category,
            $request->period
        );

        Goal::create([
            'user_id'  => Auth::id(),
            'title'    => $request->title,
            'category' => $request->category,
            'period'   => $request->period,
            'target'   => $request->target,
            'current'  => min($current, $request->target),
            'unit'     => $request->unit,
            'note'     => $request->note,
        ]);

        return redirect()->route('goal.index')
               ->with('success', '🎯 Goal set! Existing logs have been counted automatically.');
    }

    public function update(Request $request, Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) abort(403);

        $request->validate([
            'current' => 'required|integer|min:0|max:' . $goal->target,
        ]);

        $goal->update(['current' => $request->current]);

        return back()->with('success', '✅ Progress updated!');
    }

    public function destroy(Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) abort(403);
        $goal->delete();
        return back()->with('success', 'Goal deleted.');
    }

    private function countExistingLogs(string $category, string $period): int
    {
        $userId = Auth::id();
        $from   = $period === 'weekly'
                ? now()->startOfWeek()
                : now()->startOfMonth();

        $exerciseTypes = ['running', 'walking', 'strength', 'yoga'];

        return match ($category) {
            'reflection' => Reflection::where('user_id', $userId)
                                ->where('created_at', '>=', $from)->count(),
            'exercise'   => Activity::where('user_id', $userId)
                                ->whereIn('type', $exerciseTypes)
                                ->where('created_at', '>=', $from)->count(),
            'reading'    => Activity::where('user_id', $userId)
                                ->where('type', 'reading')
                                ->where('created_at', '>=', $from)->count(),
            'study'      => Activity::where('user_id', $userId)
                                ->where('type', 'study')
                                ->where('created_at', '>=', $from)->count(),
            default      => 0,
        };
    }
}
