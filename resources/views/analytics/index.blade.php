<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics — Memo Diary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
    .logo-icon { width:40px; height:40px; border-radius:12px; overflow:hidden; flex-shrink:0; }
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
    .hero-card { background:linear-gradient(140deg,#1A1E2E 0%,#252D45 60%,#2D3555 100%); border-radius:20px; padding:36px; position:relative; overflow:hidden; color:white; margin-bottom:28px; }
    .hero-card::before { content:""; position:absolute; width:300px; height:300px; background:radial-gradient(circle,rgba(91,127,166,.3) 0%,transparent 70%); top:-80px; right:-60px; border-radius:50%; }
    .hero-eyebrow { font-size:11px; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:#93B8D8; margin-bottom:8px; position:relative; }
    .hero-title { font-family:'DM Serif Display',serif; font-size:32px; color:white; margin-bottom:6px; position:relative; }
    .hero-sub { font-size:14px; color:rgba(255,255,255,.45); position:relative; }
    .stat-badge { background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1); border-radius:14px; padding:16px; text-align:center; }
    .stat-val { font-family:'DM Serif Display',serif; font-size:28px; color:white; line-height:1; margin-bottom:4px; }
    .stat-lbl { font-size:10px; color:rgba(255,255,255,.35); letter-spacing:.08em; text-transform:uppercase; }
    .kpi-card { background:white; border-radius:16px; padding:20px 22px; }
    .kpi-label { font-size:11px; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:10px; }
    .kpi-val { font-family:'DM Serif Display',serif; font-size:32px; color:var(--ink); line-height:1; margin-bottom:4px; }
    .kpi-hint { font-size:12px; color:var(--ink-muted); }
    .section-label { font-size:11px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:12px; margin-top:24px; }
    .section-label:first-child { margin-top:0; }
    .chart-card { background:white; border-radius:16px; padding:22px; }
    .prog-bg { height:8px; background:var(--cream); border-radius:4px; overflow:hidden; }
    .prog-fill { height:8px; border-radius:4px; }
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
      <span style="font-size:15px;font-weight:600;color:var(--ink);">Analytics</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">

      <div class="hero-card">
        <div class="row align-items-center position-relative">
          <div class="col-6">
            <div class="hero-eyebrow">Analytics</div>
            <div class="hero-title mb-2">Your Growth Analytics 📊</div>
            <div class="hero-sub">Visualize your progress, mood trends, and activity patterns.</div>
          </div>
          <div class="col-6">
            <div class="row g-2">
              <div class="col-3"><div class="stat-badge"><div class="stat-val">{{ $totalReflections }}</div><div class="stat-lbl">Reflections</div></div></div>
              <div class="col-3"><div class="stat-badge"><div class="stat-val">{{ $totalActivities }}</div><div class="stat-lbl">Activities</div></div></div>
              <div class="col-3"><div class="stat-badge"><div class="stat-val">{{ $avgMood ? number_format($avgMood,1) : '--' }}</div><div class="stat-lbl">Avg Mood</div></div></div>
              <div class="col-3"><div class="stat-badge"><div class="stat-val">{{ $avgGoalProgress }}%</div><div class="stat-lbl">Goal Rate</div></div></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-3"><div class="kpi-card shadow-sm" style="border-top:3px solid var(--amber)"><div class="kpi-label">This Month Reflections</div><div class="kpi-val">{{ $thisMonthRef }}</div><div class="kpi-hint">days logged this month</div></div></div>
        <div class="col-3"><div class="kpi-card shadow-sm" style="border-top:3px solid var(--sage)"><div class="kpi-label">This Month Activities</div><div class="kpi-val">{{ $thisMonthAct }}</div><div class="kpi-hint">activities logged</div></div></div>
        <div class="col-3"><div class="kpi-card shadow-sm" style="border-top:3px solid var(--blue)"><div class="kpi-label">Total Active Minutes</div><div class="kpi-val">{{ number_format($totalDuration) }}</div><div class="kpi-hint">minutes across all activities</div></div></div>
        <div class="col-3"><div class="kpi-card shadow-sm" style="border-top:3px solid var(--purple)"><div class="kpi-label">Avg Goal Progress</div><div class="kpi-val">{{ $avgGoalProgress }}%</div><div class="kpi-hint">across {{ $totalGoals }} goals</div></div></div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-8">
          <div class="chart-card shadow-sm">
            <div class="section-label">30-Day Mood Trend</div>
            @if($totalReflections > 0)
              <canvas id="moodTrendChart" height="100"></canvas>
            @else
              <div style="text-align:center;padding:40px;color:var(--ink-muted);font-size:13px;">No data yet. Start logging reflections!</div>
            @endif
          </div>
        </div>
        <div class="col-4">
          <div class="chart-card shadow-sm h-100">
            <div class="section-label">Mood Distribution</div>
            @if($totalReflections > 0)
              <canvas id="moodDistChart" height="200"></canvas>
            @else
              <div style="text-align:center;padding:40px;color:var(--ink-muted);font-size:13px;">No mood data yet.</div>
            @endif
          </div>
        </div>
      </div>

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

      <div class="row g-4 mb-4">
        <div class="col-4">
          <div class="chart-card shadow-sm">
            <div class="section-label">Activity Breakdown</div>
            @if($activityByType->isNotEmpty())
              <canvas id="activityTypeChart" height="200"></canvas>
            @else
              <div style="text-align:center;padding:40px;color:var(--ink-muted);font-size:13px;">No activities logged yet.</div>
            @endif
          </div>
        </div>
        <div class="col-4">
          <div class="chart-card shadow-sm">
            <div class="section-label">Goal Progress</div>
            @if($goals->isNotEmpty())
              @php $catIcons=['reflection'=>'✍️','exercise'=>'🏃','reading'=>'📚','study'=>'💻','other'=>'🎵']; $catColors2=['reflection'=>'#8B6BAE','exercise'=>'#7A9E7E','reading'=>'#C8863A','study'=>'#5B7FA6','other'=>'#8C8680']; @endphp
              @foreach($goals as $goal)
              @php $progress=$goal->progress; @endphp
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-1" style="font-size:13px;">
                  <span>{{ $catIcons[$goal->category]??'🎯' }} {{ Str::limit($goal->title, 18) }}</span>
                  <span style="font-weight:700;color:{{ $catColors2[$goal->category]??'#8C8680' }}">{{ $progress }}%</span>
                </div>
                <div class="prog-bg"><div class="prog-fill" style="width:{{ $progress }}%;background:{{ $catColors2[$goal->category]??'#8C8680' }};"></div></div>
                <div style="font-size:11px;color:var(--ink-muted);margin-top:3px;">{{ $goal->current }} / {{ $goal->target }} {{ $goal->unit }}</div>
              </div>
              @endforeach
            @else
              <div style="text-align:center;padding:40px;color:var(--ink-muted);font-size:13px;">No goals set yet.</div>
            @endif
          </div>
        </div>
        <div class="col-4">
          <div class="chart-card shadow-sm">
            <div class="section-label">Most Used Tags</div>
            @if($allTags->isNotEmpty())
              @php $tagEmojis=['work'=>'💼','exercise'=>'🏃','study'=>'📚','family'=>'❤️','growth'=>'🌱','mental'=>'🧘','achievement'=>'🎯']; $maxTagCount=$allTags->max(); @endphp
              @foreach($allTags as $tag => $count)
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-1" style="font-size:13px;">
                  <span>{{ $tagEmojis[$tag]??'🏷' }} {{ ucfirst($tag) }}</span>
                  <span style="font-weight:600;color:var(--ink-muted);">{{ $count }}回</span>
                </div>
                <div class="prog-bg"><div class="prog-fill" style="width:{{ round($count/$maxTagCount*100) }}%;background:var(--amber);"></div></div>
              </div>
              @endforeach
            @else
              <div style="text-align:center;padding:40px;color:var(--ink-muted);font-size:13px;">No tags used yet.</div>
            @endif
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    Chart.defaults.font.family = "'DM Sans', sans-serif";
    Chart.defaults.color = '#8C8680';
    @if($totalReflections > 0)
    new Chart(document.getElementById('moodTrendChart'), {
      type: 'line',
      data: {
        labels: {!! json_encode($last30Days->pluck('date')) !!},
        datasets: [{ label:'Mood Score', data: {!! json_encode($last30Days->map(fn($d) => $d['mood'])) !!}, borderColor:'#C8863A', backgroundColor:'rgba(200,134,58,.08)', borderWidth:2, pointRadius:3, pointBackgroundColor:'#C8863A', tension:0.4, fill:true, spanGaps:true }]
      },
      options: { responsive:true, scales:{ y:{ min:0, max:5, ticks:{ stepSize:1 }, grid:{ color:'rgba(28,26,23,.06)' } }, x:{ ticks:{ maxTicksLimit:10 }, grid:{ display:false } } }, plugins:{ legend:{ display:false } } }
    });
    new Chart(document.getElementById('moodDistChart'), {
      type: 'doughnut',
      data: {
        labels: ['😞 Very Low','😐 Low','🙂 Okay','😊 Good','🤩 Excellent'],
        datasets: [{ data: {!! json_encode([$moodCounts[1]??0,$moodCounts[2]??0,$moodCounts[3]??0,$moodCounts[4]??0,$moodCounts[5]??0]) !!}, backgroundColor:['#C4716A','#BA7517','#C8863A','#7A9E7E','#5DCAA5'], borderWidth:0 }]
      },
      options: { cutout:'65%', plugins:{ legend:{ position:'bottom', labels:{ padding:12, font:{ size:11 } } } } }
    });
    @endif
    new Chart(document.getElementById('monthlyRefChart'), {
      type: 'bar',
      data: { labels: {!! json_encode($monthlyReflections->pluck('month')) !!}, datasets: [{ label:'Reflections', data: {!! json_encode($monthlyReflections->pluck('count')) !!}, backgroundColor:'rgba(200,134,58,.7)', borderRadius:6 }] },
      options: { responsive:true, scales:{ y:{ beginAtZero:true, ticks:{ stepSize:1 }, grid:{ color:'rgba(28,26,23,.06)' } }, x:{ grid:{ display:false } } }, plugins:{ legend:{ display:false } } }
    });
    new Chart(document.getElementById('monthlyActChart'), {
      type: 'bar',
      data: { labels: {!! json_encode($monthlyActivities->pluck('month')) !!}, datasets: [{ label:'Activities', data: {!! json_encode($monthlyActivities->pluck('count')) !!}, backgroundColor:'rgba(122,158,126,.7)', borderRadius:6 }] },
      options: { responsive:true, scales:{ y:{ beginAtZero:true, ticks:{ stepSize:1 }, grid:{ color:'rgba(28,26,23,.06)' } }, x:{ grid:{ display:false } } }, plugins:{ legend:{ display:false } } }
    });
    @if($activityByType->isNotEmpty())
    new Chart(document.getElementById('activityTypeChart'), {
      type: 'doughnut',
      data: {
        labels: {!! json_encode($activityByType->map(fn($a) => ucfirst($a->type).' ('.$a->count.'回)')->values()) !!},
        datasets: [{ data: {!! json_encode($activityByType->pluck('count')->values()) !!}, backgroundColor:['#7A9E7E','#5B7FA6','#C8863A','#8B6BAE','#C4716A','#4A9B8E','#8C8680'].slice(0, {{ $activityByType->count() }}), borderWidth:0 }]
      },
      options: { cutout:'65%', plugins:{ legend:{ position:'bottom', labels:{ padding:10, font:{ size:11 } } } } }
    });
    @endif
  </script>
</body>
</html>
