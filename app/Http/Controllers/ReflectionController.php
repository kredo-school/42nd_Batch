<?php
namespace App\Http\Controllers;

use App\Models\Reflection;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReflectionController extends Controller
{
    public function create()
{
    // 今週のMoodデータ
    $weekStart = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY);
    $weekMoods = [];
    for ($i = 0; $i < 7; $i++) {
        $date = $weekStart->copy()->addDays($i);
        $ref  = \App\Models\Reflection::where('user_id', auth()->id())
                    ->whereDate('created_at', $date)->latest()->first();
        $weekMoods[] = [
            'label' => $date->format('D'),
            'mood'  => $ref ? $ref->mood : null,
            'today' => $date->isToday(),
        ];
    }

    // 全期間のMood分布（1〜5の件数）
    $moodCounts = \App\Models\Reflection::where('user_id', auth()->id())
                    ->selectRaw('mood, count(*) as count')
                    ->groupBy('mood')
                    ->pluck('count', 'mood')
                    ->toArray();
    $totalReflections = array_sum($moodCounts);

    return view('reflection.create', compact('weekMoods', 'moodCounts', 'totalReflections'));
}

    public function store(Request $request)
    {
        $request->validate([
            'mood'    => 'required|integer|min:1|max:5',
            'journal' => 'required|string|max:500',
        ]);

        $todos = array_values(array_filter($request->todo ?? [], fn($t) => trim($t) !== ''));

        Reflection::create([
            'user_id'  => Auth::id(),
            'mood'     => $request->mood,
            'journal'  => $request->journal,
            'grateful' => $request->grateful,
            'improve'  => $request->improve,
            'todos'    => !empty($todos) ? $todos : null,
            'tags'     => json_decode($request->tags ?? '[]', true),
        ]);

        $goal = Goal::where('user_id', Auth::id())
                    ->where('category', 'reflection')->first();
        if ($goal && $goal->current < $goal->target) {
            $goal->increment('current');
        }

        return redirect()->route('dashboard')
               ->with('success', '✍️ Reflection saved! Your goal progress has been updated.');
    }

    public function edit(Reflection $reflection)
    {
        if ($reflection->user_id !== Auth::id()) abort(403);
        return view('reflection.edit', compact('reflection'));
    }

    public function update(Request $request, Reflection $reflection)
    {
        if ($reflection->user_id !== Auth::id()) abort(403);

        $request->validate([
            'mood'    => 'required|integer|min:1|max:5',
            'journal' => 'required|string|max:500',
        ]);

        $todos = array_values(array_filter($request->todo ?? [], fn($t) => trim($t) !== ''));

        $reflection->update([
            'mood'     => $request->mood,
            'journal'  => $request->journal,
            'grateful' => $request->grateful,
            'improve'  => $request->improve,
            'todos'    => !empty($todos) ? $todos : null,
            'tags'     => json_decode($request->tags ?? '[]', true),
        ]);

        return redirect()->route('dashboard')
               ->with('success', '✏️ Reflection updated successfully!');
    }

    public function destroy(Reflection $reflection)
    {
        if ($reflection->user_id !== Auth::id()) abort(403);
        $reflection->delete();
        return back()->with('success', '🗑 Reflection deleted.');
    }
}
