<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Memo Diary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --amber:#C8863A; --amber-light:#E8B86D; --amber-pale:#F5E4C8;
      --sage:#7A9E7E; --sage-pale:#E4EDE5;
      --rose:#C4716A; --rose-pale:#F5E0DE;
      --blue:#5B7FA6; --blue-pale:#DDE7F2;
      --purple:#8B6BAE; --purple-pale:#EDE8F5;
      --ink:#1C1A17; --ink-muted:#8C8680;
      --cream:#F5F0E8; --bg:#F0EAE0;
      --sidebar-w:240px;
    }
    body { font-family:'DM Sans',sans-serif; background:var(--bg); }
    .sidebar { width:var(--sidebar-w); background:var(--ink); min-height:100vh; position:fixed; top:0; left:0; display:flex; flex-direction:column; padding:28px 0 24px; z-index:100; }
    .sidebar-logo { padding:0 18px 24px; border-bottom:1px solid rgba(255,255,255,.07); margin-bottom:16px; display:flex; align-items:center; gap:10px; }
    .logo-icon { width:40px; height:40px; background:linear-gradient(135deg,var(--amber),#D4956A); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:18px; overflow:hidden; padding:0; background:none; }
    .logo-name { font-family:'DM Serif Display',serif; font-size:18px; color:white; }
    .logo-tagline { font-size:10px; color:rgba(255,255,255,.3); letter-spacing:.08em; }
    .nav-section-label { font-size:9.5px; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:rgba(255,255,255,.25); padding:8px 22px 4px; }
    .sidebar .nav-link { display:flex; align-items:center; gap:10px; padding:10px 22px; color:rgba(255,255,255,.5); font-size:13px; font-weight:500; border-left:3px solid transparent; border-radius:0; transition:.18s; }
    .sidebar .nav-link:hover { background:rgba(255,255,255,.05); color:rgba(255,255,255,.85); }
    .sidebar .nav-link.active { background:rgba(200,134,58,.12); border-left-color:var(--amber); color:white; font-weight:600; }
    .sidebar-footer { margin-top:auto; padding:16px 22px 0; border-top:1px solid rgba(255,255,255,.07); }
    .user-avatar { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,var(--amber),var(--rose)); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:white; flex-shrink:0; }
    .logout-btn { background:none; border:none; cursor:pointer; display:flex; align-items:center; gap:10px; padding:10px 0; width:100%; margin-top:10px; color:rgba(255,255,255,.4); font-size:13px; font-weight:500; font-family:'DM Sans',sans-serif; transition:.18s; }
    .logout-btn:hover { color:var(--rose); }
    .main-content { margin-left:var(--sidebar-w); min-height:100vh; }
    .topbar { height:60px; background:rgba(253,250,244,.9); backdrop-filter:blur(16px); border-bottom:1px solid rgba(28,26,23,.07); position:sticky; top:0; z-index:50; }
    .topbar-btn { width:36px; height:36px; border-radius:50%; background:white; border:1px solid rgba(28,26,23,.1); display:flex; align-items:center; justify-content:center; font-size:15px; text-decoration:none; color:inherit; }
    .hero-card { background:linear-gradient(140deg,#2A2420 0%,#3D3228 60%,#4A3C2E 100%); border-radius:20px; padding:36px; position:relative; overflow:hidden; color:white; }
    .hero-card::before { content:""; position:absolute; width:300px; height:300px; background:radial-gradient(circle,rgba(200,134,58,.2) 0%,transparent 70%); top:-80px; right:-60px; border-radius:50%; }
    .hero-eyebrow { font-size:11px; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:var(--amber-light); margin-bottom:8px; }
    .hero-title { font-family:'DM Serif Display',serif; font-size:32px; }
    .kpi-card { background:white; border-radius:16px; padding:22px 24px; }
    .kpi-label { font-size:11px; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:12px; }
    .kpi-empty-val { font-family:'DM Serif Display',serif; font-size:34px; color:#D3D1C7; line-height:1; margin-bottom:6px; }
    .kpi-val-active { font-family:'DM Serif Display',serif; font-size:34px; color:var(--ink); line-height:1; margin-bottom:6px; }
    .kpi-empty-hint { font-size:12px; color:#B4B2A9; }
    .kpi-hint-active { font-size:12px; color:var(--sage); font-weight:600; }
    .empty-state { background:white; border-radius:16px; padding:40px 24px; text-align:center; }
    .empty-icon { font-size:40px; margin-bottom:14px; opacity:.4; }
    .empty-title { font-size:15px; font-weight:600; color:var(--ink); margin-bottom:6px; }
    .empty-sub { font-size:13px; color:var(--ink-muted); line-height:1.6; margin-bottom:20px; }
    .quick-card { border-radius:14px; padding:18px; cursor:pointer; transition:transform .15s, filter .15s, box-shadow .15s; user-select:none; }
    .quick-card:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(28,26,23,.15); filter:brightness(.92); }
    .quick-card:active { transform:scale(.96); filter:brightness(.82); box-shadow:none; }
    .qc-icon { font-size:24px; margin-bottom:8px; }
    .qc-title { font-size:14px; font-weight:700; }
    .qc-sub { font-size:11px; opacity:.65; margin-top:2px; }
    .mood-circle-empty { width:48px; height:48px; border-radius:50%; margin:0 auto 6px; background:#F1EFE8; border:2px dashed #D3D1C7; }
    .mood-circle-filled { width:48px; height:48px; border-radius:50%; margin:0 auto 6px; display:flex; align-items:center; justify-content:center; font-size:22px; }
    .mood-today-empty { width:48px; height:48px; border-radius:50%; margin:0 auto 6px; background:var(--blue-pale); border:2px dashed var(--blue); display:flex; align-items:center; justify-content:center; font-size:18px; }
    .mood-lbl { font-size:11px; color:var(--ink-muted); font-weight:500; text-align:center; }
    .mood-lbl-today { font-size:11px; color:var(--blue); font-weight:700; text-align:center; }
    .activity-row { display:flex; align-items:center; gap:10px; padding:8px 0; border-bottom:1px solid rgba(28,26,23,.06); }
    .activity-row:last-child { border-bottom:none; }
    .activity-icon { width:36px; height:36px; border-radius:10px; background:var(--sage-pale); display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
    .ref-card { border-radius:12px; padding:14px 16px; margin-bottom:10px; }
    .ref-meta { font-size:10.5px; font-weight:700; letter-spacing:.07em; text-transform:uppercase; margin-bottom:4px; }
    .section-label { font-size:11px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:12px; margin-top:24px; }
    .section-label:first-child { margin-top:0; }
    .getstarted-banner { background:linear-gradient(135deg,var(--amber-pale),#FDF3E3); border:1.5px solid rgba(200,134,58,.25); border-radius:16px; padding:20px 24px; }
  </style>
</head>
<body>
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

  <div class="main-content">
    <div class="topbar d-flex align-items-center px-4">
      <span class="text-muted" style="font-size:12px;">Memo Diary</span>
      <span class="text-muted mx-2" style="opacity:.4;">›</span>
      <span style="font-size:15px;font-weight:600;color:var(--ink);">Home Dashboard</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">

      @if(session('success'))
      <div class="alert d-flex align-items-center gap-2 mb-3 rounded-3"
           style="background:#F0FDF4;border:1px solid #BBF7D0;color:#166534;font-size:13px;padding:12px 16px;">
        <span>✅</span><span>{{ session('success') }}</span>
      </div>
      @endif

      <div class="hero-card mb-4">
        <div class="row align-items-center position-relative">
          <div class="col-7">
            <div class="hero-eyebrow">{{ $totalDays > 0 ? 'Welcome Back' : 'Welcome' }}</div>
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
          <div class="col-5 text-end">
            <div style="font-size:11px;color:rgba(255,255,255,.35);letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px;">Weekly Growth Score</div>
            @if($weeklyGrowthScore > 0)
              <div style="font-family:'DM Serif Display',serif;font-size:72px;line-height:1;color:var(--amber-light);">{{ $weeklyGrowthScore }}</div>
              <div style="font-size:13px;margin-top:6px;">
                @if($scoreDiff > 0)<span style="color:#5DCAA5;">↑ +{{ $scoreDiff }} vs Last Week</span>
                @elseif($scoreDiff < 0)<span style="color:#F5E0DE;">↓ {{ $scoreDiff }} vs Last Week</span>
                @else<span style="color:rgba(255,255,255,.4);">→ Same as Last Week</span>
                @endif
              </div>
              <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin-top:14px;">
                @foreach(['W1','W2','W3','This Week'] as $i => $lbl)
                @php $isThis=($i===3); $barH=$isThis?max(8,round($weeklyGrowthScore/100*60)):rand(15,45); @endphp
                <div style="text-align:center;">
                  <div style="display:flex;align-items:flex-end;justify-content:center;height:60px;margin-bottom:4px;">
                    <div style="width:28px;border-radius:4px 4px 0 0;height:{{ $barH }}px;background:{{ $isThis ? 'var(--amber)' : 'rgba(255,255,255,.2)' }};"></div>
                  </div>
                  <div style="font-size:9px;color:{{ $isThis ? 'var(--amber-light)' : 'rgba(255,255,255,.35)' }};font-weight:{{ $isThis ? '700' : '400' }};">{{ $lbl }}</div>
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

      <div class="row g-4">
        <div class="col-8">

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
                    <div><div style="font-size:13px;font-weight:600;color:var(--ink);">{{ $label }}</div><div style="font-size:11px;color:var(--ink-muted);">{{ $sub }}</div></div>
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
                <div class="ref-meta" style="color:{{ $moodColors[$ref->mood] }};">{{ $ref->created_at->diffForHumans() }} · Mood {{ $moodEmojis[$ref->mood] }} {{ $ref->mood }}/5</div>
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
            <div class="mt-2"><a href="{{ route('activity.create') }}" class="btn fw-semibold" style="background:var(--sage-pale);color:var(--sage);border-radius:10px;font-size:13px;border:none;">🏃 Log Another Activity</a></div>
          </div>
          @endif

        </div>

        <div class="col-4">
          <div class="section-label" style="margin-top:0">Quick Actions</div>
          <div class="row g-2 mb-4">
            <div class="col-6"><a href="{{ route('reflection.create') }}" style="text-decoration:none"><div class="quick-card" style="background:#E8E4DC;color:var(--ink)"><div class="qc-icon">✍️</div><div class="qc-title">Write Reflection</div><div class="qc-sub" style="color:var(--ink-muted)">Today's Log</div></div></a></div>
            <div class="col-6"><a href="{{ route('activity.create') }}" style="text-decoration:none"><div class="quick-card" style="background:var(--sage-pale);color:var(--sage)"><div class="qc-icon">🏃</div><div class="qc-title">Activity Log</div><div class="qc-sub">Exercise & Study</div></div></a></div>
            <div class="col-6"><a href="{{ route('goal.index') }}" style="text-decoration:none"><div class="quick-card" style="background:var(--purple-pale);color:var(--purple)"><div class="qc-icon">🎯</div><div class="qc-title">Set Goals</div><div class="qc-sub">Monthly Progress</div></div></a></div>
            <div class="col-6"><div class="quick-card" style="background:var(--amber-pale);color:var(--amber)"><div class="qc-icon">✨</div><div class="qc-title">AI Report</div><div class="qc-sub">Available after 7 logs</div></div></div>
          </div>

          <div class="section-label">This Month's Goals</div>
          @if($monthlyGoals->isEmpty())
            <div class="empty-state shadow-sm">
              <div class="empty-icon">🎯</div>
              <div class="empty-title">No goals set yet</div>
              <div class="empty-sub">Set your first monthly goal<br>to start tracking progress.</div>
              <a href="{{ route('goal.create') }}" class="btn fw-semibold px-4" style="background:var(--purple-pale);color:var(--purple);border-radius:10px;font-size:13px;border:none;text-decoration:none;">🎯 Set First Goal</a>
            </div>
          @else
            @php $catColors=['reflection'=>['color'=>'#8B6BAE'],'exercise'=>['color'=>'#7A9E7E'],'reading'=>['color'=>'#C8863A'],'study'=>['color'=>'#5B7FA6'],'other'=>['color'=>'#8C8680']]; $catIcons=['reflection'=>'✍️','exercise'=>'🏃','reading'=>'📚','study'=>'💻','other'=>'🎵']; @endphp
            <div class="card border-0 shadow-sm rounded-4 p-3">
              @foreach($monthlyGoals as $goal)
              @php $c=$catColors[$goal->category]??$catColors['other']; $progress=$goal->progress; @endphp
              <div class="mb-3 {{ !$loop->last ? 'pb-3' : '' }}" style="{{ !$loop->last ? 'border-bottom:1px solid rgba(28,26,23,.06)' : '' }}">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span style="font-size:13px;font-weight:600;color:var(--ink);">{{ $catIcons[$goal->category]??'🎯' }} {{ Str::limit($goal->title, 22) }}</span>
                  <span style="font-size:13px;font-weight:700;color:{{ $c['color'] }}">{{ $progress }}%</span>
                </div>
                <div style="height:6px;background:var(--cream);border-radius:3px;overflow:hidden;"><div style="height:6px;background:{{ $c['color'] }};border-radius:3px;width:{{ $progress }}%;transition:width .5s;"></div></div>
                <div style="font-size:11px;color:var(--ink-muted);margin-top:4px;">{{ $goal->current }} / {{ $goal->target }} {{ $goal->unit }} @if($progress>=100) · ✅ Completed! @elseif($progress>=70) · 📈 On track @else · 💪 Keep going @endif</div>
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
