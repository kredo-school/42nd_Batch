<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics — Memo Diary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/memo-diary.css') }}" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>

  {{-- ════ SIDEBAR ════ --}}
  <div class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon"><img src="{{ asset('images/logo.jpg') }}" class="logo-img"></div>
      <div><div class="logo-name">Memo Diary</div><div class="logo-tagline">SELF-GROWTH LOG</div></div>
    </div>
    <div class="nav-section-label">Main</div>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}"><span>🏠</span> Home</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('reflection.create') }}"><span>✍️</span> Daily Reflection</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('activity.create') }}"><span>🏃</span> Activity Log</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('goal.index') }}"><span>🎯</span> Goal Tracking</a></li>
      <li class="nav-item"><a class="nav-link active" href="{{ route('analytics') }}"><span>📊</span> Analytics</a></li>
    </ul>
    <div class="nav-section-label">Personal</div>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}"><span>👤</span> Profile</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><span>✨</span> AI Report</a></li>
    </ul>
    <div class="sidebar-footer">
      <div class="d-flex align-items-center gap-2">
        @if(auth()->user()->avatar)
  <img src="{{ Storage::url(auth()->user()->avatar) }}" class="user-avatar user-avatar-img">
@else
  <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
@endif
        <div>
          <div class="sidebar-username">{{ auth()->user()->name }}</div>
          <div class="sidebar-role">Member</div>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn"><span>🚪</span> Log Out</button>
      </form>
    </div>
  </div>

  {{-- ════ MAIN ════ --}}
  <div class="main-content">
    <div class="topbar d-flex align-items-center px-4">
      <span class="topbar-breadcrumb">Memo Diary</span>
      <span class="topbar-sep">›</span>
      <span class="topbar-current">Analytics</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">

      {{-- Hero --}}
      <div class="hero-card hero-card-navy">
        <div class="row align-items-center position-relative">
          <div class="col-6">
            <div class="hero-eyebrow hero-eyebrow-navy">Analytics</div>
            <div class="hero-title mb-2">Your Growth Analytics 📊</div>
            <div class="hero-sub">Visualize your progress, mood trends, and activity patterns.</div>
          </div>
          <div class="col-6">
            <div class="d-flex gap-2">
              <div class="stat-badge flex-fill">
                <div class="stat-val">{{ $totalReflections }}</div>
                <div class="stat-lbl">Reflections</div>
              </div>
              <div class="stat-badge flex-fill">
                <div class="stat-val">{{ $totalActivities }}</div>
                <div class="stat-lbl">Activities</div>
              </div>
              <div class="stat-badge flex-fill">
                <div class="stat-val">{{ $avgMood ? number_format($avgMood,1) : '--' }}</div>
                <div class="stat-lbl">Avg Mood</div>
              </div>
              <div class="stat-badge flex-fill">
                <div class="stat-val">{{ $avgGoalProgress }}%</div>
                <div class="stat-lbl">Goal Rate</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- KPI Cards --}}
      <div class="row g-3 mb-4">
        <div class="col-3">
          <div class="analytics-kpi-card shadow-sm analytics-border-amber">
            <div class="analytics-kpi-label">This Month Reflections</div>
            <div class="analytics-kpi-val">{{ $thisMonthRef }}</div>
            <div class="analytics-kpi-hint">days logged this month</div>
          </div>
        </div>
        <div class="col-3">
          <div class="analytics-kpi-card shadow-sm analytics-border-sage">
            <div class="analytics-kpi-label">This Month Activities</div>
            <div class="analytics-kpi-val">{{ $thisMonthAct }}</div>
            <div class="analytics-kpi-hint">activities logged</div>
          </div>
        </div>
        <div class="col-3">
          <div class="analytics-kpi-card shadow-sm analytics-border-blue">
            <div class="analytics-kpi-label">Total Active Minutes</div>
            <div class="analytics-kpi-val">{{ number_format($totalDuration) }}</div>
            <div class="analytics-kpi-hint">minutes across all activities</div>
          </div>
        </div>
        <div class="col-3">
          <div class="analytics-kpi-card shadow-sm analytics-border-purple">
            <div class="analytics-kpi-label">Avg Goal Progress</div>
            <div class="analytics-kpi-val">{{ $avgGoalProgress }}%</div>
            <div class="analytics-kpi-hint">across {{ $totalGoals }} goals</div>
          </div>
        </div>
      </div>

      {{-- Charts Row 1 --}}
      <div class="row g-4 mb-4">
        <div class="col-8">
          <div class="chart-card shadow-sm">
            <div class="section-label">30-Day Mood Trend</div>
            @if($totalReflections > 0)
              <canvas id="moodTrendChart" height="100"></canvas>
            @else
              <div class="analytics-empty">No data yet. Start logging reflections!</div>
            @endif
          </div>
        </div>
        <div class="col-4">
          <div class="chart-card shadow-sm h-100">
            <div class="section-label">Mood Distribution</div>
            @if($totalReflections > 0)
              <canvas id="moodDistChart" height="200"></canvas>
            @else
              <div class="analytics-empty">No mood data yet.</div>
            @endif
          </div>
        </div>
      </div>

      {{-- Charts Row 2 --}}
      <div class="row g-4 mb-4">
        <div class="col-6">
          <div class="chart-card shadow-sm">
            <div class="section-label">Monthly Reflections (6 Months)</div>
            <canvas id="monthlyRefChart" height="140"></canvas>
          </div>
        </div>
        <div class="col-6">
          <div class="chart-card shadow-sm">
            <div class="section-label">Monthly Activities (6 Months)</div>
            <canvas id="monthlyActChart" height="140"></canvas>
          </div>
        </div>
      </div>

      {{-- Charts Row 3 --}}
      <div class="row g-4 mb-4">
        <div class="col-4">
          <div class="chart-card shadow-sm">
            <div class="section-label">Activity Breakdown</div>
            @if($activityByType->isNotEmpty())
              <canvas id="activityTypeChart" height="200"></canvas>
            @else
              <div class="analytics-empty">No activities logged yet.</div>
            @endif
          </div>
        </div>

        <div class="col-4">
          <div class="chart-card shadow-sm">
            <div class="section-label">Goal Progress</div>
            @if($goals->isNotEmpty())
              @php
                $catIcons2  = ['reflection'=>'✍️','exercise'=>'🏃','reading'=>'📚','study'=>'💻','other'=>'🎵'];
                $catClass2  = ['reflection'=>'goal-progress-reflection','exercise'=>'goal-progress-exercise','reading'=>'goal-progress-reading','study'=>'goal-progress-study','other'=>'goal-progress-other'];
              @endphp
              @foreach($goals as $goal)
              @php
                $cls      = $catClass2[$goal->category] ?? 'goal-progress-other';
                $progress = $goal->progress;
              @endphp
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                  <span class="analytics-goal-label">{{ $catIcons2[$goal->category] ?? '🎯' }} {{ Str::limit($goal->title, 18) }}</span>
                  <span class="analytics-goal-val {{ $cls }}">{{ $progress }}%</span>
                </div>
                <div class="prog-bg">
                  <div class="prog-fill {{ $cls }}" style="width:{{ $progress }}%;"></div>
                </div>
                <div class="analytics-goal-sub">{{ $goal->current }} / {{ $goal->target }} {{ $goal->unit }}</div>
              </div>
              @endforeach
            @else
              <div class="analytics-empty">No goals set yet.</div>
            @endif
          </div>
        </div>

        <div class="col-4">
          <div class="chart-card shadow-sm">
            <div class="section-label">Most Used Tags</div>
            @if($allTags->isNotEmpty())
              @php
                $tagEmojis   = ['work'=>'💼','exercise'=>'🏃','study'=>'📚','family'=>'❤️','growth'=>'🌱','mental'=>'🧘','achievement'=>'🎯'];
                $maxTagCount = $allTags->max();
              @endphp
              @foreach($allTags as $tag => $count)
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                  <span class="analytics-tag-label">{{ $tagEmojis[$tag] ?? '🏷' }} {{ ucfirst($tag) }}</span>
                  <span class="analytics-tag-count">{{ $count }}回</span>
                </div>
                <div class="prog-bg">
                  <div class="prog-fill tag-progress-fill" style="width:{{ round($count/$maxTagCount*100) }}%;"></div>
                </div>
              </div>
              @endforeach
            @else
              <div class="analytics-empty">No tags used yet.</div>
            @endif
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script>
    Chart.defaults.font.family = "'DM Sans', sans-serif";
    Chart.defaults.color = '#8C8680';

    @if($totalReflections > 0)
    new Chart(document.getElementById('moodTrendChart'), {
      type: 'line',
      data: {
        labels: {!! json_encode($last30Days->pluck('date')) !!},
        datasets: [{
          label: 'Mood Score',
          data: {!! json_encode($last30Days->map(fn($d) => $d['mood'])) !!},
          borderColor: '#C8863A',
          backgroundColor: 'rgba(200,134,58,.08)',
          borderWidth: 2,
          pointRadius: 3,
          pointBackgroundColor: '#C8863A',
          tension: 0.4,
          fill: true,
          spanGaps: true,
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { min:0, max:5, ticks:{ stepSize:1 }, grid:{ color:'rgba(28,26,23,.06)' } },
          x: { ticks:{ maxTicksLimit:10 }, grid:{ display:false } }
        },
        plugins: { legend:{ display:false } }
      }
    });

    new Chart(document.getElementById('moodDistChart'), {
      type: 'doughnut',
      data: {
        labels: ['😞 Very Low','😐 Low','🙂 Okay','😊 Good','🤩 Excellent'],
        datasets: [{
          data: {!! json_encode([$moodCounts[1]??0,$moodCounts[2]??0,$moodCounts[3]??0,$moodCounts[4]??0,$moodCounts[5]??0]) !!},
          backgroundColor: ['#C4716A','#BA7517','#C8863A','#7A9E7E','#5DCAA5'],
          borderWidth: 0
        }]
      },
      options: {
        cutout: '65%',
        plugins: { legend:{ position:'bottom', labels:{ padding:12, font:{ size:11 } } } }
      }
    });
    @endif

    new Chart(document.getElementById('monthlyRefChart'), {
      type: 'bar',
      data: {
        labels: {!! json_encode($monthlyReflections->pluck('month')) !!},
        datasets: [{
          label: 'Reflections',
          data: {!! json_encode($monthlyReflections->pluck('count')) !!},
          backgroundColor: 'rgba(200,134,58,.7)',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero:true, ticks:{ stepSize:1 }, grid:{ color:'rgba(28,26,23,.06)' } },
          x: { grid:{ display:false } }
        },
        plugins: { legend:{ display:false } }
      }
    });

    new Chart(document.getElementById('monthlyActChart'), {
      type: 'bar',
      data: {
        labels: {!! json_encode($monthlyActivities->pluck('month')) !!},
        datasets: [{
          label: 'Activities',
          data: {!! json_encode($monthlyActivities->pluck('count')) !!},
          backgroundColor: 'rgba(122,158,126,.7)',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero:true, ticks:{ stepSize:1 }, grid:{ color:'rgba(28,26,23,.06)' } },
          x: { grid:{ display:false } }
        },
        plugins: { legend:{ display:false } }
      }
    });

    @if($activityByType->isNotEmpty())
    new Chart(document.getElementById('activityTypeChart'), {
      type: 'doughnut',
      data: {
        labels: {!! json_encode($activityByType->map(fn($a) => ucfirst($a->type).' ('.$a->count.'回)')->values()) !!},
        datasets: [{
          data: {!! json_encode($activityByType->pluck('count')->values()) !!},
          backgroundColor: ['#7A9E7E','#5B7FA6','#C8863A','#8B6BAE','#C4716A','#4A9B8E','#8C8680'].slice(0, {{ $activityByType->count() }}),
          borderWidth: 0
        }]
      },
      options: {
        cutout: '65%',
        plugins: { legend:{ position:'bottom', labels:{ padding:10, font:{ size:11 } } } }
      }
    });
    @endif
  </script>
</body>
</html>
