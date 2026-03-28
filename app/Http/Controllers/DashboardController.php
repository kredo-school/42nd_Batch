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

        // 今週（月〜日）のデータ
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekEnd   = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $weekMoods = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $ref  = Reflection::where('user_id', $user->id)
                        ->whereDate('created_at', $date)->latest()->first();
            $weekMoods[] = [
                'label' => $date->format('D'),
                'date'  => $date->toDateString(),
                'mood'  => $ref ? $ref->mood : null,
                'today' => $date->isToday(),
            ];
        }

        // ── Weekly Growth Score 計算 ──
        // 今週の実績
        $weekReflections = Reflection::where('user_id', $user->id)
                            ->whereBetween('created_at', [$weekStart, $weekEnd])->get();
        $weekActivities  = Activity::where('user_id', $user->id)
                            ->whereBetween('created_at', [$weekStart, $weekEnd])->count();

        $weekReflectionCount = $weekReflections->count();
        $weekAvgMood         = $weekReflections->avg('mood') ?? 0;

        // スコア計算（100点満点）
        // ① リフレクション達成率（最大40点）: 週7日中何日書いたか
        $reflectionScore = min(40, round($weekReflectionCount / 7 * 40));

        // ② 気分スコア（最大30点）: 平均mood × 6
        $moodScore = $weekAvgMood > 0 ? min(30, round($weekAvgMood * 6)) : 0;

        // ③ アクティビティ（最大20点）: 週3回以上で満点
        $activityScore = min(20, round($weekActivities / 3 * 20));

        // ④ ゴール進捗（最大10点）
        $goalScore = 0;
        if ($monthlyGoals->isNotEmpty()) {
            $avgProgress = $monthlyGoals->avg(fn($g) => $g->progress);
            $goalScore   = min(10, round($avgProgress / 100 * 10));
        }

        $weeklyGrowthScore = $reflectionScore + $moodScore + $activityScore + $goalScore;

        // 先週のスコアと比較
        $lastWeekStart = $weekStart->copy()->subWeek();
        $lastWeekEnd   = $weekEnd->copy()->subWeek();
        $lastWeekRefs  = Reflection::where('user_id', $user->id)
                            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->get();
        $lastWeekActs  = Activity::where('user_id', $user->id)
                            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();

        $lastReflScore  = min(40, round($lastWeekRefs->count() / 7 * 40));
        $lastMoodScore  = $lastWeekRefs->avg('mood') > 0 ? min(30, round($lastWeekRefs->avg('mood') * 6)) : 0;
        $lastActScore   = min(20, round($lastWeekActs / 3 * 20));
        $lastWeekScore  = $lastReflScore + $lastMoodScore + $lastActScore;
        $scoreDiff      = $weeklyGrowthScore - $lastWeekScore;

        return view('dashboard', compact(
            'reflections', 'totalDays', 'avgMood',
            'weekMoods', 'monthlyExercise', 'recentActivities', 'monthlyGoals',
            'weeklyGrowthScore', 'scoreDiff', 'weekReflectionCount', 'weekActivities'
        ));
    }
}
