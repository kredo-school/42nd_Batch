<?php
namespace App\Http\Controllers;

use App\Models\Goal;
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
            'title'    => 'required|string|max:100',
            'category' => 'required|string',
            'period'   => 'required|string',
            'target'   => 'required|integer|min:1',
            'unit'     => 'nullable|string|max:20',
            'note'     => 'nullable|string|max:200',
        ]);

        Goal::create([
            'user_id'  => Auth::id(),
            'title'    => $request->title,
            'category' => $request->category,
            'period'   => $request->period,
            'target'   => $request->target,
            'current'  => 0,
            'unit'     => $request->unit,
            'note'     => $request->note,
        ]);

        return redirect()->route('goal.index')
               ->with('success', '🎯 Goal created!');
    }

    public function update(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $request->validate([
            'current' => 'required|integer|min:0',
        ]);

        $goal->update(['current' => min($request->current, $goal->target)]);

        return redirect()->route('goal.index')
               ->with('success', '✅ Goal progress updated!');
    }

    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);
        $goal->delete();

        return redirect()->route('goal.index')
               ->with('success', '🗑 Goal deleted.');
    }
}
