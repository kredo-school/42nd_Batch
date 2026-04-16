<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Memo Diary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/memo-diary.css') }}" rel="stylesheet">
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
      <li class="nav-item"><a class="nav-link active" href="{{ url('/dashboard') }}"><span>🏠</span> Home</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('reflection.create') }}"><span>✍️</span> Daily Reflection</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('activity.create') }}"><span>🏃</span> Activity Log</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('goal.index') }}"><span>🎯</span> Goal Tracking</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('analytics') }}"><span>📊</span> Analytics</a></li>
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
      <span class="topbar-current">Home Dashboard</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">

      @if(session('success'))
      <div class="alert d-flex align-items-center gap-2 mb-3 rounded-3 alert-success-custom">
        <span>✅</span><span>{{ session('success') }}</span>
      </div>
      @endif

      {{-- Hero --}}
      <div class="hero-card hero-card-dark mb-4">
        <div class="row align-items-center position-relative">
          <div class="col-7">
            <div class="hero-eyebrow hero-eyebrow-amber">{{ $totalDays > 0 ? 'Welcome Back' : 'Welcome' }}</div>
            <div class="hero-title mb-2">Hello, {{ auth()->user()->name }} 👋</div>
            <div class="hero-sub dashboard-hero-sub mb-4">
              {{ $totalDays > 0 ? 'Great work! Keep the streak going 🔥' : 'Your journey starts here. Record your first reflection today!' }}
            </div>
            <div class="d-flex gap-2">
              <a href="{{ route('reflection.create') }}" class="btn text-white fw-semibold btn-amber-sm">
                ✍️ {{ $totalDays > 0 ? "Write Today's Reflection" : 'Write First Reflection' }}
              </a>
              <a href="{{ route('activity.create') }}" class="btn fw-semibold btn-ghost-white">
                🏃 Log an Activity
              </a>
            </div>
          </div>
          <div class="col-5 text-end">
            <div class="dashboard-score-label">Weekly Growth Score</div>
            @if($weeklyGrowthScore > 0)
              <div class="dashboard-score-val">{{ $weeklyGrowthScore }}</div>
              <div class="mt-2">
                @if($scoreDiff > 0)<span class="dashboard-score-diff-up">↑ +{{ $scoreDiff }} vs Last Week</span>
                @elseif($scoreDiff < 0)<span class="dashboard-score-diff-down">↓ {{ $scoreDiff }} vs Last Week</span>
                @else<span class="dashboard-score-diff-same">→ Same as Last Week</span>@endif
              </div>
              <div class="dashboard-bar-grid">
                @foreach($weeklyScores as $ws)
                @php
                  $barH     = $ws['score'] > 0 ? max(6, round($ws['score'] / 100 * 60)) : 4;
                  $isThis   = $ws['isThis'];
                  $isLast   = $ws['isLast'];
                  $barClass = $isThis ? 'dashboard-bar dashboard-bar-this' : ($isLast ? 'dashboard-bar dashboard-bar-last' : 'dashboard-bar dashboard-bar-prev');
                @endphp
                <div class="dashboard-bar-item">
                  <div class="dashboard-bar-wrap">
                    <div class="{{ $barClass }}" style="height:{{ $barH }}px;"></div>
                  </div>
                  @if($ws['score'] > 0)
                    <div class="{{ $isThis ? 'dashboard-bar-score-this' : 'dashboard-bar-score-other' }}">{{ $ws['score'] }}</div>
                  @else
                    <div class="dashboard-bar-score-empty">--</div>
                  @endif
                  <div class="{{ $isThis ? 'dashboard-bar-lbl-this' : 'dashboard-bar-lbl-other' }}">{{ $ws['label'] }}</div>
                </div>
                @endforeach
              </div>
            @else
              <div class="dashboard-score-empty">--</div>
              <div class="dashboard-score-empty-hint">No data yet · Start logging!</div>
            @endif
          </div>
        </div>
      </div>

      {{-- Banner --}}
      @if($totalDays === 0)
      <div class="getstarted-banner mb-4 d-flex align-items-center gap-3">
        <span style="font-size:28px;">🚀</span>
        <div class="flex-grow-1">
          <div class="dashboard-banner-title">Get started with Memo Diary</div>
          <div class="dashboard-banner-sub">Complete the steps below to set up your growth journey.</div>
        </div>
        <div class="dashboard-banner-count">0 / 3 done</div>
      </div>
      @else
      <div class="getstarted-banner mb-4 d-flex align-items-center gap-3">
        <span style="font-size:28px;">🔥</span>
        <div class="flex-grow-1">
          <div class="dashboard-banner-title">You're on a roll!</div>
          <div class="dashboard-banner-sub">{{ $totalDays }} day{{ $totalDays > 1 ? 's' : '' }} logged so far. Keep it up!</div>
        </div>
        <div class="dashboard-banner-count">{{ min($totalDays, 3) }} / 3 done</div>
      </div>
      @endif

      {{-- KPI Cards --}}
      @php $goalAchievement = $monthlyGoals->isNotEmpty() ? round($monthlyGoals->avg(fn($g) => $g->progress)) : null; @endphp
      <div class="row g-3 mb-4">
        <div class="col-3">
          <div class="kpi-card shadow-sm {{ $totalDays > 0 ? 'kpi-border-amber' : 'kpi-border-grey' }}">
            <div class="kpi-label">Days Logged</div>
            <div class="{{ $totalDays > 0 ? 'kpi-val-active' : 'kpi-empty-val' }}">{{ $totalDays }}</div>
            <div class="{{ $totalDays > 0 ? 'kpi-hint-active' : 'kpi-empty-hint' }}">{{ $totalDays > 0 ? 'Keep it up! 🔥' : 'Start your first log →' }}</div>
          </div>
        </div>
        <div class="col-3">
          <div class="kpi-card shadow-sm {{ $monthlyExercise > 0 ? 'kpi-border-sage' : 'kpi-border-grey' }}">
            <div class="kpi-label">This Month's Exercise</div>
            <div class="{{ $monthlyExercise > 0 ? 'kpi-val-active' : 'kpi-empty-val' }}">{{ $monthlyExercise }}</div>
            <div class="{{ $monthlyExercise > 0 ? 'kpi-hint-active' : 'kpi-empty-hint' }}">{{ $monthlyExercise > 0 ? 'Activities logged 💪' : 'No activity logged yet' }}</div>
          </div>
        </div>
        <div class="col-3">
          <div class="kpi-card shadow-sm {{ $avgMood ? 'kpi-border-blue' : 'kpi-border-grey' }}">
            <div class="kpi-label">Avg Mood Score</div>
            <div class="{{ $avgMood ? 'kpi-val-active' : 'kpi-empty-val' }}">{{ $avgMood ? number_format(floor($avgMood * 10) / 10, 1) : '--' }}</div>
            <div class="{{ $avgMood ? 'kpi-hint-active' : 'kpi-empty-hint' }}">{{ $avgMood ? 'Average mood score' : 'Log mood to see score' }}</div>
          </div>
        </div>
        <div class="col-3">
          <div class="kpi-card shadow-sm {{ $goalAchievement !== null ? 'kpi-border-purple' : 'kpi-border-grey' }}">
            <div class="kpi-label">Goal Achievement Rate</div>
            <div class="{{ $goalAchievement !== null ? 'kpi-val-active' : 'kpi-empty-val' }}">{{ $goalAchievement !== null ? $goalAchievement.'%' : '--%' }}</div>
            <div class="{{ $goalAchievement !== null ? 'kpi-hint-purple' : 'kpi-empty-hint' }}">{{ $goalAchievement !== null ? 'Avg across goals 🎯' : 'Set your first goal →' }}</div>
          </div>
        </div>
      </div>

      {{-- Main Grid --}}
      <div class="row g-4">

        {{-- Left --}}
        <div class="col-8">

          {{-- Mood Trend --}}
          <div class="section-label">This Week's Mood Trend</div>
          <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
            <div class="d-flex gap-2 justify-content-between">
              @php $moodEmojis=['','😞','😐','🙂','😊','🤩']; @endphp
              @foreach($weekMoods as $day)
              <div class="text-center flex-fill">
                @if($day['today'])
                  @if($day['mood'])
                    <div class="mood-circle-filled dashboard-mood-bg-{{ $day['mood'] }}" style="border:2px solid var(--blue);">{{ $moodEmojis[$day['mood']] }}</div>
                  @else
                    <div class="mood-today-empty">＋</div>
                  @endif
                  <div class="mood-lbl-today">Today</div>
                @else
                  @if($day['mood'])
                    <div class="mood-circle-filled dashboard-mood-bg-{{ $day['mood'] }}">{{ $moodEmojis[$day['mood']] }}</div>
                  @else
                    <div class="mood-circle-empty"></div>
                  @endif
                  <div class="mood-lbl">{{ $day['label'] }}</div>
                @endif
              </div>
              @endforeach
            </div>
            @if($totalDays === 0)
            <div class="dashboard-mood-hint">Log your mood each day to see your weekly trend here.</div>
            @endif
          </div>

          {{-- Score Breakdown --}}
          @if($weeklyGrowthScore > 0)
          <div class="section-label">This Week's Score Breakdown</div>
          <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="row g-3">
              @php
                $breakdownItems = [
                  ['✍️','Reflection',$weekReflectionCount.' days this week','var(--amber)',round($weekReflectionCount/7*100)],
                  ['🏃','Activity',$weekActivities.' times this week','var(--sage)',min(100,round($weekActivities/3*100))],
                  ['😊','Mood',($avgMood?number_format(floor($avgMood*10)/10,1):'--').'/5 avg','var(--blue)',$avgMood?min(100,round($avgMood/5*100)):0],
                  ['🎯','Goals',$goalAchievement!==null?$goalAchievement.'% achieved':'No goals','var(--purple)',$goalAchievement??0],
                ];
              @endphp
              @foreach($breakdownItems as [$icon,$label,$sub,$color,$pct])
              <div class="col-6">
                <div class="dashboard-breakdown-card">
                  <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="dashboard-breakdown-icon">{{ $icon }}</span>
                    <div>
                      <div class="dashboard-breakdown-title">{{ $label }}</div>
                      <div class="dashboard-breakdown-sub">{{ $sub }}</div>
                    </div>
                  </div>
                  <div class="dashboard-breakdown-bar-bg">
                    <div class="dashboard-breakdown-bar-fill" style="background:{{ $color }};width:{{ $pct }}%;"></div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endif

          {{-- Recent Reflections --}}
          <div class="section-label">Recent Reflections</div>
          @if($reflections->isEmpty())
            <div class="empty-state shadow-sm">
              <div class="empty-icon">✍️</div>
              <div class="empty-title">No reflections yet</div>
              <div class="empty-sub">Write your first reflection to start tracking<br>your thoughts and growth journey.</div>
              <a href="{{ route('reflection.create') }}" class="btn text-white fw-semibold px-4 btn-amber-sm">✍️ Write Today's Reflection</a>
            </div>
          @else
            @foreach($reflections as $ref)
            <div class="ref-card dashboard-ref-bg-{{ $ref->mood }}">
              <div class="d-flex justify-content-between align-items-start mb-1">
                <div class="ref-meta dashboard-ref-meta-{{ $ref->mood }}">
                  {{ $ref->created_at->diffForHumans() }} · Mood {{ $moodEmojis[$ref->mood] }} {{ $ref->mood }}/5
                </div>
                <div class="d-flex gap-2 align-items-center">
                  <a href="{{ route('reflection.edit', $ref) }}" class="dashboard-ref-edit-btn">✏️ Edit</a>
                  <form method="POST" action="{{ route('reflection.destroy', $ref) }}" onsubmit="return confirm('Delete this reflection?')" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="dashboard-ref-del-btn">🗑 Delete</button>
                  </form>
                </div>
              </div>
              <div class="dashboard-ref-text">{{ Str::limit($ref->journal, 100) }}</div>
              @if(!empty($ref->tags))
              <div class="d-flex flex-wrap gap-1 mt-2">
                @foreach($ref->tags as $tag)
                  <span class="dashboard-ref-tag">{{ $tag }}</span>
                @endforeach
              </div>
              @endif
              @if(!empty($ref->todos))
              <div class="mt-2 d-flex flex-column gap-1">
                @foreach($ref->todos as $todo)
                <div class="dashboard-ref-todo">
                  <div class="dashboard-ref-todo-box"></div>{{ $todo }}
                </div>
                @endforeach
              </div>
              @endif
            </div>
            @endforeach
            <a href="{{ route('reflection.create') }}" class="btn fw-semibold mt-2 btn-amber-pale-sm">✍️ Write Today's Reflection</a>
          @endif

          {{-- Recent Activities --}}
          @if($recentActivities->isNotEmpty())
          <div class="section-label">Recent Activities</div>
          <div class="card border-0 shadow-sm rounded-4 p-3">
            @php $activityIcons=['running'=>'🏃','walking'=>'🚶','strength'=>'💪','yoga'=>'🧘','reading'=>'📚','study'=>'💻','other'=>'🎵']; @endphp
            @foreach($recentActivities as $act)
            <div class="activity-row">
              <div class="activity-icon">{{ $activityIcons[$act->type] ?? '🏃' }}</div>
              <div class="flex-grow-1">
                <div class="dashboard-act-type">{{ $act->type }}</div>
                <div class="dashboard-act-sub">{{ $act->duration }} min @if($act->amount) · {{ $act->amount }} @endif @if($act->intensity) · Intensity {{ $act->intensity }} @endif</div>
              </div>
              <div class="dashboard-act-time">{{ $act->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
            <div class="mt-2">
              <a href="{{ route('activity.create') }}" class="btn fw-semibold btn-sage-pale-sm">🏃 Log Another Activity</a>
            </div>
          </div>
          @endif

        </div>

        {{-- Right --}}
        <div class="col-4">
          <div class="section-label-notop">Quick Actions</div>
          <div class="row g-2 mb-4">
            <div class="col-6">
              <a href="{{ route('reflection.create') }}" style="text-decoration:none">
                <div class="quick-card" style="background:var(--cream);color:var(--ink-muted)">
                  <div class="qc-icon">✍️</div>
                  <div class="qc-title" style="color:var(--ink-muted)">Write Reflection</div>
                  <div class="qc-sub">Today's Log</div>
                </div>
              </a>
            </div>
            <div class="col-6">
              <a href="{{ route('activity.create') }}" style="text-decoration:none">
                <div class="quick-card" style="background:var(--sage-pale);color:var(--sage)">
                  <div class="qc-icon">🏃</div>
                  <div class="qc-title">Activity Log</div>
                  <div class="qc-sub">Exercise & Study</div>
                </div>
              </a>
            </div>
            <div class="col-6">
              <a href="{{ route('goal.index') }}" style="text-decoration:none">
                <div class="quick-card" style="background:var(--purple-pale);color:var(--purple)">
                  <div class="qc-icon">🎯</div>
                  <div class="qc-title">Set Goals</div>
                  <div class="qc-sub">Monthly Progress</div>
                </div>
              </a>
            </div>
            <div class="col-6">
              <div class="quick-card" style="background:var(--amber-pale);color:var(--amber)">
                <div class="qc-icon">✨</div>
                <div class="qc-title">AI Report</div>
                <div class="qc-sub">Available after 7 logs</div>
              </div>
            </div>
          </div>

          {{-- This Month's Goals --}}
          <div class="section-label">This Month's Goals</div>
          @if($monthlyGoals->isEmpty())
            <div class="empty-state shadow-sm">
              <div class="empty-icon">🎯</div>
              <div class="empty-title">No goals set yet</div>
              <div class="empty-sub">Set your first monthly goal<br>to start tracking progress.</div>
              <a href="{{ route('goal.create') }}" class="btn fw-semibold px-4 btn-purple-pale-sm">🎯 Set First Goal</a>
            </div>
          @else
            @php
              $catColorClass=['reflection'=>'reflection','exercise'=>'exercise','reading'=>'reading','study'=>'study','other'=>'other'];
              $catIcons=['reflection'=>'✍️','exercise'=>'🏃','reading'=>'📚','study'=>'💻','other'=>'🎵'];
            @endphp
            <div class="card border-0 shadow-sm rounded-4 p-3">
              @foreach($monthlyGoals as $goal)
              @php $cls = $catColorClass[$goal->category] ?? 'other'; $progress = $goal->progress; @endphp
              <div class="mb-3 {{ !$loop->last ? 'pb-3 dashboard-goal-border' : '' }}">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="dashboard-goal-title-text">{{ $catIcons[$goal->category]??'🎯' }} {{ Str::limit($goal->title, 22) }}</span>
                  <span class="dashboard-goal-pct dashboard-goal-color-{{ $cls }}">{{ $progress }}%</span>
                </div>
                <div class="prog-bg">
                  <div class="prog-fill dashboard-goal-fill-{{ $cls }}" style="width:{{ $progress }}%;"></div>
                </div>
                <div class="dashboard-goal-sub">
                  {{ $goal->current }} / {{ $goal->target }} {{ $goal->unit }}
                  @if($progress>=100) · ✅ Completed! @elseif($progress>=70) · 📈 On track @else · 💪 Keep going @endif
                </div>
              </div>
              @endforeach
              <a href="{{ route('goal.index') }}" class="dashboard-goal-link">View all goals →</a>
            </div>
          @endif

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
