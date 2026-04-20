<?php
namespace App\Http\Controllers;

use App\Models\Reflection;
use App\Models\Activity;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AnalyticsController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // Data Analytics が OFF の場合はデータを返さない
    if (!$user->privacy_data_analytics) {
        return view('analytics.index', [
            'totalReflections'   => 0,
            'totalActivities'    => 0,
            'totalGoals'         => 0,
            'avgMood'            => null,
            'totalDuration'      => 0,
            'thisMonthRef'       => 0,
            'thisMonthAct'       => 0,
            'goals'              => collect(),
            'avgGoalProgress'    => 0,
            'moodCounts'         => [],
            'last30Days'         => collect(),
            'monthlyReflections' => collect(),
            'monthlyActivities'  => collect(),
            'activityByType'     => collect(),
            'allTags'            => collect(),
            'analyticsDisabled'  => true,
        ]);
    }

    // 以下は既存のコード...

        // Mood分布
        $moodCounts = Reflection::where('user_id', $user->id)
                        ->selectRaw('mood, count(*) as count')
                        ->groupBy('mood')
                        ->pluck('count', 'mood')
                        ->toArray();

        // Activity種別集計
        $activityByType = Activity::where('user_id', $user->id)
                            ->selectRaw('type, count(*) as count, sum(duration) as total_duration')
                            ->groupBy('type')
                            ->get();

        // 月別Reflection件数（過去6ヶ月）
        $monthlyReflections = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $count = Reflection::where('user_id', $user->id)
                        ->whereYear('created_at', $month->year)
                        ->whereMonth('created_at', $month->month)
                        ->count();
            $monthlyReflections->push([
                'month' => $month->format('M Y'),
                'count' => $count,
            ]);
        }

        // 月別Activity件数（過去6ヶ月）
        $monthlyActivities = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $count = Activity::where('user_id', $user->id)
                        ->whereYear('created_at', $month->year)
                        ->whereMonth('created_at', $month->month)
                        ->count();
            $monthlyActivities->push([
                'month' => $month->format('M Y'),
                'count' => $count,
            ]);
        }

        // 統計サマリー
        $totalReflections  = Reflection::where('user_id', $user->id)->count();
        $totalActivities   = Activity::where('user_id', $user->id)->count();
        $totalDuration     = Activity::where('user_id', $user->id)->sum('duration');
        $avgMood           = Reflection::where('user_id', $user->id)->avg('mood');
        $totalGoals        = Goal::where('user_id', $user->id)->count();
        $goals             = Goal::where('user_id', $user->id)->get();
        $avgGoalProgress   = $goals->isNotEmpty() ? round($goals->avg(fn($g) => $g->progress)) : 0;

        // 今月の集計
        $thisMonthRef  = Reflection::where('user_id', $user->id)
                            ->whereMonth('created_at', $now->month)
                            ->whereYear('created_at', $now->year)->count();
        $thisMonthAct  = Activity::where('user_id', $user->id)
                            ->whereMonth('created_at', $now->month)
                            ->whereYear('created_at', $now->year)->count();

        // タグ集計
        $allTags = Reflection::where('user_id', $user->id)
                        ->whereNotNull('tags')->get()
                        ->flatMap(fn($r) => $r->tags ?? [])
                        ->countBy()->sortDesc()->take(7);

        return view('analytics.index', compact(
            'last30Days', 'moodCounts', 'activityByType',
            'monthlyReflections', 'monthlyActivities',
            'totalReflections', 'totalActivities', 'totalDuration',
            'avgMood', 'totalGoals', 'avgGoalProgress',
            'thisMonthRef', 'thisMonthAct', 'allTags', 'goals'
        ));
    }
}
