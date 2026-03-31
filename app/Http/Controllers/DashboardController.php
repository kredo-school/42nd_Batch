<?php
namespace App\Http\Controllers;

use App\Models\Reflection;
use App\Models\Activity;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user             = Auth::user();
        $reflections      = Reflection::where('user_id', $user->id)->latest()->take(3)->get();
        $totalDays        = Reflection::where('user_id', $user->id)->count();
        $avgMood          = Reflection::where('user_id', $user->id)->avg('mood');
        $monthlyExercise  = Activity::where('user_id', $user->id)
                              ->whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year)->count();
        $recentActivities = Activity::where('user_id', $user->id)->latest()->take(3)->get();
        $monthlyGoals     = Goal::where('user_id', $user->id)->latest()->take(3)->get();

        // 今週（月〜日）のMoodデータ
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekEnd   = Carbon::now()->endOfWeek(Carbon::SUNDAY);
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

        // ── 先月のスコア ──
        $lastMonthStart = Carbon::now()->startOfMonth()->subMonth();
        $lastMonthEnd   = Carbon::now()->startOfMonth()->subDay();
        $lastMonthDays  = $lastMonthStart->daysInMonth;

        $lastMonthRefs  = Reflection::where('user_id', $user->id)
                            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->get();
        $lastMonthActs  = Activity::where('user_id', $user->id)
                            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

        $lmRScore = min(40, round($lastMonthRefs->count() / $lastMonthDays * 40));
        $lmMScore = $lastMonthRefs->avg('mood') > 0 ? min(30, round($lastMonthRefs->avg('mood') * 6)) : 0;
        $lmAScore = min(20, round($lastMonthActs / 12 * 20));
        $lmGScore = $monthlyGoals->isNotEmpty() ? min(10, round($monthlyGoals->avg(fn($g) => $g->progress) / 100 * 10)) : 0;
        $lastMonthScore = $lmRScore + $lmMScore + $lmAScore + $lmGScore;

        // ── 先週のスコア ──
        $lastWeekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->subWeek();
        $lastWeekEnd   = $lastWeekStart->copy()->endOfWeek(Carbon::SUNDAY);

        $lastWeekRefs  = Reflection::where('user_id', $user->id)
                            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->get();
        $lastWeekActs  = Activity::where('user_id', $user->id)
                            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();

        $lwRScore      = min(40, round($lastWeekRefs->count() / 7 * 40));
        $lwMScore      = $lastWeekRefs->avg('mood') > 0 ? min(30, round($lastWeekRefs->avg('mood') * 6)) : 0;
        $lwAScore      = min(20, round($lastWeekActs / 3 * 20));
        $lastWeekScore = $lwRScore + $lwMScore + $lwAScore + $lmGScore;

        // ── 今週のスコア ──
        $weekReflections     = Reflection::where('user_id', $user->id)
                                ->whereBetween('created_at', [$weekStart, $weekEnd])->get();
        $weekActivities      = Activity::where('user_id', $user->id)
                                ->whereBetween('created_at', [$weekStart, $weekEnd])->count();
        $weekReflectionCount = $weekReflections->count();

        $rScore = min(40, round($weekReflectionCount / 7 * 40));
        $mScore = $weekReflections->avg('mood') > 0 ? min(30, round($weekReflections->avg('mood') * 6)) : 0;
        $aScore = min(20, round($weekActivities / 3 * 20));
        $gScore = $monthlyGoals->isNotEmpty() ? min(10, round($monthlyGoals->avg(fn($g) => $g->progress) / 100 * 10)) : 0;
        $weeklyGrowthScore = $rScore + $mScore + $aScore + $gScore;

        $scoreDiff = $weeklyGrowthScore - $lastWeekScore;

        // ── バー表示用データ（3本）──
        $weeklyScores = [
            ['label' => 'Last Month', 'score' => $lastMonthScore,     'isThis' => false, 'isLast' => false],
            ['label' => 'Last Week',  'score' => $lastWeekScore,      'isThis' => false, 'isLast' => true],
            ['label' => 'This Week',  'score' => $weeklyGrowthScore,  'isThis' => true,  'isLast' => false],
        ];

        return view('dashboard', compact(
            'reflections', 'totalDays', 'avgMood',
            'weekMoods', 'monthlyExercise', 'recentActivities', 'monthlyGoals',
            'weeklyGrowthScore', 'scoreDiff', 'weekReflectionCount', 'weekActivities',
            'weeklyScores'
        ));
    }
}
