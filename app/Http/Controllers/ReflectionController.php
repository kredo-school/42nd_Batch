<?php
namespace App\Http\Controllers;

use App\Models\Reflection;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReflectionController extends Controller
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

        return view('reflection.create', compact('weekMoods', 'totalReflections', 'moodCounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mood'    => 'required|integer|min:1|max:5',
            'journal' => 'required|string|max:500',
        ]);

        $todos = array_filter($request->input('todo', []), fn($t) => !empty(trim($t)));
        $tags  = json_decode($request->input('tags', '[]'), true) ?? [];

        Reflection::create([
            'user_id'  => Auth::id(),
            'mood'     => $request->mood,
            'journal'  => $request->journal,
            'grateful' => $request->grateful,
            'improve'  => $request->improve,
            'todos'    => array_values($todos),
            'tags'     => $tags,
        ]);

        $goal = Goal::where('user_id', Auth::id())
                    ->where('category', 'reflection')
                    ->first();
        if ($goal && $goal->current < $goal->target) {
            $goal->increment('current');
        }

        return redirect()->route('dashboard')
               ->with('success', '✍️ Reflection saved! Keep up the great work!');
    }

    public function edit(Reflection $reflection)
    {
        $this->authorize('update', $reflection);
        return view('reflection.edit', compact('reflection'));
    }

    public function update(Request $request, Reflection $reflection)
    {
        $this->authorize('update', $reflection);

        $request->validate([
            'mood'    => 'required|integer|min:1|max:5',
            'journal' => 'required|string|max:500',
        ]);

        $todos = array_filter($request->input('todo', []), fn($t) => !empty(trim($t)));
        $tags  = json_decode($request->input('tags', '[]'), true) ?? [];

        $reflection->update([
            'mood'     => $request->mood,
            'journal'  => $request->journal,
            'grateful' => $request->grateful,
            'improve'  => $request->improve,
            'todos'    => array_values($todos),
            'tags'     => $tags,
        ]);

        return redirect()->route('dashboard')
               ->with('success', '✏️ Reflection updated!');
    }

    public function destroy(Reflection $reflection)
    {
        $this->authorize('delete', $reflection);
        $reflection->delete();

        return redirect()->route('dashboard')
               ->with('success', '🗑 Reflection deleted.');
    }
}
