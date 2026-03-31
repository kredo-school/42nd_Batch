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
      <div class="logo-icon"><img src="{{ asset('images/logo.jpg') }}" style="width:40px;height:40px;object-fit:cover;border-radius:12px;display:block;"></div>
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
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div>
          <div style="font-size:12.5px;font-weight:600;color:rgba(255,255,255,.8);">{{ auth()->user()->name }}</div>
          <div style="font-size:10px;color:rgba(255,255,255,.35);">Member</div>
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
      <span class="text-muted" style="font-size:12px;">Memo Diary</span>
      <span class="text-muted mx-2" style="opacity:.4;">›</span>
      <span style="font-size:15px;font-weight:600;color:var(--ink);">Home Dashboard</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">

      {{-- ✅ 成功メッセージ --}}
      @if(session('success'))
      <div class="alert d-flex align-items-center gap-2 mb-3 rounded-3"
           style="background:#F0FDF4;border:1px solid #BBF7D0;color:#166534;font-size:13px;padding:12px 16px;">
        <span>✅</span><span>{{ session('success') }}</span>
      </div>
      @endif

      {{-- Hero --}}
      <div class="hero-card hero-card-dark mb-4">
        <div class="row align-items-center position-relative">
          <div class="col-7">
            <div class="hero-eyebrow" style="color:var(--amber-light);">{{ $totalDays > 0 ? 'Welcome Back' : 'Welcome' }}</div>
            <div class="hero-title mb-2">Hello, {{ auth()->user()->name }} 👋</div>
            <div style="font-size:14px;color:rgba(255,255,255,.45);" class="mb-4">
              {{ $totalDays > 0 ? 'Great work! Keep the streak going 🔥' : 'Your journey starts here. Record your first reflection today!' }}
            </div>
            <div class="d-flex gap-2">
              <a href="{{ route('reflection.create') }}" class="btn text-white fw-semibold"
                 style="background:var(--amber);border-radius:10px;font-size:13px;">
                ✍️ {{ $totalDays > 0 ? "Write Today's Reflection" : 'Write First Reflection' }}
              </a>
              <a href="{{ route('activity.create') }}" class="btn fw-semibold"
                 style="background:rgba(255,255,255,.1);color:white;border:1px solid rgba(255,255,255,.2);border-radius:10px;font-size:13px;">
                🏃 Log an Activity
              </a>
            </div>
          </div>

          {{-- Weekly Growth Score --}}
          <div class="col-5 text-end">
            <div style="font-size:11px;color:rgba(255,255,255,.35);letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px;">Weekly Growth Score</div>
            @if($weeklyGrowthScore > 0)
              <div style="font-family:'DM Serif Display',serif;font-size:72px;line-height:1;color:var(--amber-light);">{{ $weeklyGrowthScore }}</div>
              <div style="font-size:13px;margin-top:6px;">
                @if($scoreDiff > 0)<span style="color:#5DCAA5;">↑ +{{ $scoreDiff }} vs Last Week</span>
                @elseif($scoreDiff < 0)<span style="color:#F5E0DE;">↓ {{ $scoreDiff }} vs Last Week</span>
                @else<span style="color:rgba(255,255,255,.4);">→ Same as Last Week</span>@endif
              </div>
              {{-- 3本バー: Last Month / Last Week / This Week --}}
              <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:14px;">
                @foreach($weeklyScores as $ws)
                @php
                  $barH     = $ws['score'] > 0 ? max(6, round($ws['score'] / 100 * 60)) : 4;
                  $isThis   = $ws['isThis'];
                  $isLast   = $ws['isLast'];
                  $barColor = $isThis ? 'var(--amber)' : ($isLast ? 'rgba(200,134,58,.45)' : 'rgba(255,255,255,.2)');
                  $lblColor = $isThis ? 'var(--amber-light)' : 'rgba(255,255,255,.4)';
                @endphp
                <div style="text-align:center;">
                  <div style="display:flex;align-items:flex-end;justify-content:center;height:60px;margin-bottom:4px;">
                    <div style="width:32px;border-radius:4px 4px 0 0;height:{{ $barH }}px;background:{{ $barColor }};"></div>
                  </div>
                  @if($ws['score'] > 0)
                  <div style="font-size:10px;font-weight:600;color:{{ $isThis ? 'var(--amber-light)' : 'rgba(255,255,255,.6)' }};margin-bottom:2px;">{{ $ws['score'] }}</div>
                  @else
                  <div style="font-size:10px;color:rgba(255,255,255,.3);margin-bottom:2px;">--</div>
                  @endif
                  <div style="font-size:9px;color:{{ $lblColor }};font-weight:{{ $isThis ? '700' : '400' }};">{{ $ws['label'] }}</div>
                </div>
                @endforeach
              </div>
            @else
              <div style="font-family:'DM Serif Display',serif;font-size:72px;line-height:1;color:rgba(255,255,255,.15);">--</div>
              <div style="font-size:12px;color:rgba(255,255,255,.3);margin-top:6px;">No data yet · Start logging!</div>
            @endif
          </div>
        </div>
      </div>

      {{-- Banner --}}
      @if($totalDays === 0)
      <div class="getstarted-banner mb-4 d-flex align-items-center gap-3">
        <span style="font-size:28px;">🚀</span>
        <div class="flex-grow-1">
          <div style="font-size:14px;font-weight:700;color:var(--ink);">Get started with Memo Diary</div>
          <div style="font-size:12px;color:var(--ink-muted);margin-top:2px;">Complete the steps below to set up your growth journey.</div>
        </div>
        <div style="font-size:13px;font-weight:600;color:var(--amber);">0 / 3 done</div>
      </div>
      @else
      <div class="getstarted-banner mb-4 d-flex align-items-center gap-3">
        <span style="font-size:28px;">🔥</span>
        <div class="flex-grow-1">
          <div style="font-size:14px;font-weight:700;color:var(--ink);">You're on a roll!</div>
          <div style="font-size:12px;color:var(--ink-muted);margin-top:2px;">{{ $totalDays }} day{{ $totalDays > 1 ? 's' : '' }} logged so far. Keep it up!</div>
        </div>
        <div style="font-size:13px;font-weight:600;color:var(--amber);">{{ min($totalDays, 3) }} / 3 done</div>
      </div>
      @endif

      {{-- KPI Cards --}}
      @php $goalAchievement = $monthlyGoals->isNotEmpty() ? round($monthlyGoals->avg(fn($g) => $g->progress)) : null; @endphp
      <div class="row g-3 mb-4">
        <div class="col-3">
          <div class="kpi-card shadow-sm" style="border-top:3px solid {{ $totalDays > 0 ? 'var(--amber)' : '#D3D1C7' }}">
            <div class="kpi-label">Days Logged</div>
            <div class="{{ $totalDays > 0 ? 'kpi-val-active' : 'kpi-empty-val' }}">{{ $totalDays }}</div>
            <div class="{{ $totalDays > 0 ? 'kpi-hint-active' : 'kpi-empty-hint' }}">{{ $totalDays > 0 ? 'Keep it up! 🔥' : 'Start your first log →' }}</div>
          </div>
        </div>
        <div class="col-3">
          <div class="kpi-card shadow-sm" style="border-top:3px solid {{ $monthlyExercise > 0 ? 'var(--sage)' : '#D3D1C7' }}">
            <div class="kpi-label">This Month's Exercise</div>
            <div class="{{ $monthlyExercise > 0 ? 'kpi-val-active' : 'kpi-empty-val' }}">{{ $monthlyExercise }}</div>
            <div class="{{ $monthlyExercise > 0 ? 'kpi-hint-active' : 'kpi-empty-hint' }}">{{ $monthlyExercise > 0 ? 'Activities logged 💪' : 'No activity logged yet' }}</div>
          </div>
        </div>
        <div class="col-3">
          <div class="kpi-card shadow-sm" style="border-top:3px solid {{ $avgMood ? 'var(--blue)' : '#D3D1C7' }}">
            <div class="kpi-label">Avg Mood Score</div>
            <div class="{{ $avgMood ? 'kpi-val-active' : 'kpi-empty-val' }}">{{ $avgMood ? number_format($avgMood, 1) : '--' }}</div>
            <div class="{{ $avgMood ? 'kpi-hint-active' : 'kpi-empty-hint' }}">{{ $avgMood ? 'Average mood score' : 'Log mood to see score' }}</div>
          </div>
        </div>
        <div class="col-3">
          <div class="kpi-card shadow-sm" style="border-top:3px solid {{ $goalAchievement !== null ? 'var(--purple)' : '#D3D1C7' }}">
            <div class="kpi-label">Goal Achievement Rate</div>
            <div class="{{ $goalAchievement !== null ? 'kpi-val-active' : 'kpi-empty-val' }}">{{ $goalAchievement !== null ? $goalAchievement.'%' : '--%' }}</div>
            <div class="{{ $goalAchievement !== null ? 'kpi-hint-active' : 'kpi-empty-hint' }}" style="{{ $goalAchievement !== null ? 'color:var(--purple)' : '' }}">{{ $goalAchievement !== null ? 'Avg across goals 🎯' : 'Set your first goal →' }}</div>
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
              @php $moodEmojis=['','😞','😐','🙂','😊','🤩']; $moodBgColors=['','var(--rose-pale)','var(--amber-pale)','var(--amber-pale)','var(--amber-pale)','var(--sage-pale)']; @endphp
              @foreach($weekMoods as $day)
              <div class="text-center flex-fill">
                @if($day['today'])
                  @if($day['mood'])<div class="mood-circle-filled" style="background:{{ $moodBgColors[$day['mood']] }};border:2px solid var(--blue);">{{ $moodEmojis[$day['mood']] }}</div>
                  @else<div class="mood-today-empty">＋</div>@endif
                  <div class="mood-lbl-today">Today</div>
                @else
                  @if($day['mood'])<div class="mood-circle-filled" style="background:{{ $moodBgColors[$day['mood']] }};">{{ $moodEmojis[$day['mood']] }}</div>
                  @else<div class="mood-circle-empty"></div>@endif
                  <div class="mood-lbl">{{ $day['label'] }}</div>
                @endif
              </div>
              @endforeach
            </div>
            @if($totalDays === 0)
            <div class="text-center mt-3" style="font-size:12px;color:var(--ink-muted);">Log your mood each day to see your weekly trend here.</div>
            @endif
          </div>

          {{-- Score Breakdown --}}
          @if($weeklyGrowthScore > 0)
          <div class="section-label">This Week's Score Breakdown</div>
          <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="row g-3">
              @foreach([
                ['✍️','Reflection','週'.$weekReflectionCount.'日記録','var(--amber)',round($weekReflectionCount/7*100)],
                ['🏃','Activity','週'.$weekActivities.'回ログ','var(--sage)',min(100,round($weekActivities/3*100))],
                ['😊','Mood',($avgMood?number_format($avgMood,1):'--').'/5 avg','var(--blue)',$avgMood?min(100,round($avgMood/5*100)):0],
                ['🎯','Goals',$goalAchievement!==null?$goalAchievement.'%達成':'No goals','var(--purple)',$goalAchievement??0],
              ] as [$icon,$label,$sub,$color,$pct])
              <div class="col-6">
                <div style="background:var(--cream);border-radius:12px;padding:14px;">
                  <div class="d-flex align-items-center gap-2 mb-2">
                    <span style="font-size:18px;">{{ $icon }}</span>
                    <div>
                      <div style="font-size:13px;font-weight:600;color:var(--ink);">{{ $label }}</div>
                      <div style="font-size:11px;color:var(--ink-muted);">{{ $sub }}</div>
                    </div>
                  </div>
                  <div style="height:6px;background:rgba(28,26,23,.1);border-radius:3px;overflow:hidden;">
                    <div style="height:6px;background:{{ $color }};border-radius:3px;width:{{ $pct }}%;"></div>
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
              <a href="{{ route('reflection.create') }}" class="btn text-white fw-semibold px-4" style="background:var(--amber);border-radius:10px;font-size:13px;">✍️ Write Today's Reflection</a>
            </div>
          @else
            @foreach($reflections as $ref)
            @php $moodColors=['','#C4716A','#BA7517','#BA7517','#C8863A','#7A9E7E']; $moodBgs=['','var(--rose-pale)','var(--amber-pale)','var(--amber-pale)','var(--amber-pale)','var(--sage-pale)']; @endphp
            <div class="ref-card" style="background:{{ $moodBgs[$ref->mood] }};border-left:3px solid {{ $moodColors[$ref->mood] }};">
              <div class="d-flex justify-content-between align-items-start mb-1">
                <div class="ref-meta" style="color:{{ $moodColors[$ref->mood] }};">
                  {{ $ref->created_at->diffForHumans() }} · Mood {{ $moodEmojis[$ref->mood] }} {{ $ref->mood }}/5
                </div>
                <div class="d-flex gap-2 align-items-center">
                  <a href="{{ route('reflection.edit', $ref) }}" style="font-size:11px;font-weight:600;color:var(--blue);text-decoration:none;padding:2px 8px;border:1px solid var(--blue);border-radius:6px;white-space:nowrap;">✏️ Edit</a>
                  <form method="POST" action="{{ route('reflection.destroy', $ref) }}" onsubmit="return confirm('Delete this reflection?')" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" style="font-size:11px;font-weight:600;color:var(--rose);background:none;border:1px solid var(--rose);border-radius:6px;padding:2px 8px;cursor:pointer;font-family:'DM Sans',sans-serif;white-space:nowrap;">🗑 Delete</button>
                  </form>
                </div>
              </div>
              <div style="font-size:13px;color:var(--ink);line-height:1.6;">{{ Str::limit($ref->journal, 100) }}</div>
              @if(!empty($ref->tags))
              <div class="d-flex flex-wrap gap-1 mt-2">
                @foreach($ref->tags as $tag)<span style="font-size:10px;padding:2px 8px;border-radius:10px;background:rgba(28,26,23,.08);color:var(--ink-muted);font-weight:600;">{{ $tag }}</span>@endforeach
              </div>
              @endif
              @if(!empty($ref->todos))
              <div style="margin-top:8px;display:flex;flex-direction:column;gap:4px;">
                @foreach($ref->todos as $todo)
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--ink-muted);">
                  <div style="width:14px;height:14px;border-radius:4px;border:1.5px solid rgba(28,26,23,.2);flex-shrink:0;"></div>{{ $todo }}
                </div>
                @endforeach
              </div>
              @endif
            </div>
            @endforeach
            <a href="{{ route('reflection.create') }}" class="btn fw-semibold mt-2" style="background:var(--amber-pale);color:var(--amber);border-radius:10px;font-size:13px;border:none;">✍️ Write Today's Reflection</a>
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
                <div style="font-size:13px;font-weight:600;color:var(--ink);text-transform:capitalize;">{{ $act->type }}</div>
                <div style="font-size:11px;color:var(--ink-muted);">{{ $act->duration }} min @if($act->amount) · {{ $act->amount }} @endif @if($act->intensity) · Intensity {{ $act->intensity }} @endif</div>
              </div>
              <div style="font-size:11px;color:var(--ink-muted);">{{ $act->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
            <div class="mt-2">
              <a href="{{ route('activity.create') }}" class="btn fw-semibold" style="background:var(--sage-pale);color:var(--sage);border-radius:10px;font-size:13px;border:none;">🏃 Log Another Activity</a>
            </div>
          </div>
          @endif

        </div>

        {{-- Right --}}
        <div class="col-4">
          <div class="section-label" style="margin-top:0">Quick Actions</div>
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
              <a href="{{ route('goal.create') }}" class="btn fw-semibold px-4" style="background:var(--purple-pale);color:var(--purple);border-radius:10px;font-size:13px;border:none;text-decoration:none;">🎯 Set First Goal</a>
            </div>
          @else
            @php
              $catColors=['reflection'=>['color'=>'#8B6BAE'],'exercise'=>['color'=>'#7A9E7E'],'reading'=>['color'=>'#C8863A'],'study'=>['color'=>'#5B7FA6'],'other'=>['color'=>'#8C8680']];
              $catIcons=['reflection'=>'✍️','exercise'=>'🏃','reading'=>'📚','study'=>'💻','other'=>'🎵'];
            @endphp
            <div class="card border-0 shadow-sm rounded-4 p-3">
              @foreach($monthlyGoals as $goal)
              @php $c=$catColors[$goal->category]??$catColors['other']; $progress=$goal->progress; @endphp
              <div class="mb-3 {{ !$loop->last ? 'pb-3' : '' }}" style="{{ !$loop->last ? 'border-bottom:1px solid rgba(28,26,23,.06)' : '' }}">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span style="font-size:13px;font-weight:600;color:var(--ink);">{{ $catIcons[$goal->category]??'🎯' }} {{ Str::limit($goal->title, 22) }}</span>
                  <span style="font-size:13px;font-weight:700;color:{{ $c['color'] }}">{{ $progress }}%</span>
                </div>
                <div style="height:6px;background:var(--cream);border-radius:3px;overflow:hidden;">
                  <div style="height:6px;background:{{ $c['color'] }};border-radius:3px;width:{{ $progress }}%;transition:width .5s;"></div>
                </div>
                <div style="font-size:11px;color:var(--ink-muted);margin-top:4px;">
                  {{ $goal->current }} / {{ $goal->target }} {{ $goal->unit }}
                  @if($progress>=100) · ✅ Completed! @elseif($progress>=70) · 📈 On track @else · 💪 Keep going @endif
                </div>
              </div>
              @endforeach
              <a href="{{ route('goal.index') }}" style="font-size:12px;color:var(--purple);text-decoration:none;font-weight:600;">View all goals →</a>
            </div>
          @endif

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
