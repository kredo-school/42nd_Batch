<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Goal Tracking — Memo Diary</title>
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
    .hero-card { background:linear-gradient(140deg,#1E1A28 0%,#2D2640 100%); border-radius:20px; padding:36px; position:relative; overflow:hidden; color:white; margin-bottom:28px; }
    .hero-card::before { content:""; position:absolute; width:300px; height:300px; background:radial-gradient(circle,rgba(139,107,174,.25) 0%,transparent 70%); top:-80px; right:-60px; border-radius:50%; }
    .hero-eyebrow { font-size:11px; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:#C4A8E0; margin-bottom:8px; position:relative; }
    .hero-title { font-family:'DM Serif Display',serif; font-size:32px; color:white; margin-bottom:6px; position:relative; }
    .hero-sub { font-size:14px; color:rgba(255,255,255,.45); position:relative; }
    .stat-badge { background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1); border-radius:14px; padding:16px; text-align:center; }
    .stat-val { font-family:'DM Serif Display',serif; font-size:28px; color:white; line-height:1; margin-bottom:4px; }
    .stat-lbl { font-size:10px; color:rgba(255,255,255,.35); letter-spacing:.1em; text-transform:uppercase; }
    .goal-card { background:white; border-radius:16px; padding:20px 22px; margin-bottom:14px; border-left:5px solid var(--purple); position:relative; }
    .goal-title { font-size:15px; font-weight:700; color:var(--ink); margin-bottom:4px; }
    .goal-meta { font-size:12px; color:var(--ink-muted); margin-bottom:12px; }
    .goal-pct { font-family:'DM Serif Display',serif; font-size:22px; font-weight:700; }
    .prog-bg { height:8px; background:var(--cream); border-radius:4px; overflow:hidden; }
    .prog-fill { height:8px; border-radius:4px; transition:width .5s; }
    .goal-footer { display:flex; justify-content:space-between; align-items:center; margin-top:10px; font-size:12px; }
    .section-label { font-size:11px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:12px; margin-top:24px; }
    .section-label:first-child { margin-top:0; }
    .empty-state { background:white; border-radius:16px; padding:48px 24px; text-align:center; }
    .empty-icon { font-size:48px; margin-bottom:16px; opacity:.35; }
    .empty-title { font-size:16px; font-weight:600; color:var(--ink); margin-bottom:8px; }
    .empty-sub { font-size:13px; color:var(--ink-muted); line-height:1.7; margin-bottom:24px; }
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
      <li class="nav-item"><a class="nav-link active" href="{{ route('goal.index') }}"><span>🎯</span> Goal Tracking</a></li>
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
      <span style="font-size:15px;font-weight:600;color:var(--ink);">Goal Tracking</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">

      @if(session('success'))
      <div class="alert d-flex align-items-center gap-2 mb-3 rounded-3" style="background:#F0FDF4;border:1px solid #BBF7D0;color:#166534;font-size:13px;padding:12px 16px;">
        <span>✅</span><span>{{ session('success') }}</span>
      </div>
      @endif

      <div class="hero-card">
        <div class="row align-items-center position-relative">
          <div class="col-7">
            <div class="hero-eyebrow">Goals</div>
            <div class="hero-title mb-2">Goal Tracker 🎯</div>
            <div class="hero-sub mb-4">Set weekly & monthly goals and track your achievement rates.</div>
            <a href="{{ route('goal.create') }}" class="btn fw-semibold" style="background:var(--purple);color:white;border-radius:10px;font-size:13px;border:none;">+ Set New Goal</a>
          </div>
          <div class="col-5">
            <div class="row g-2 position-relative">
              <div class="col-4"><div class="stat-badge"><div class="stat-val">{{ $goals->count() }}</div><div class="stat-lbl">In Progress</div></div></div>
              <div class="col-4"><div class="stat-badge"><div class="stat-val">{{ $goals->count() > 0 ? round($goals->avg(fn($g) => $g->progress)) : '--' }}%</div><div class="stat-lbl">Avg Rate</div></div></div>
              <div class="col-4"><div class="stat-badge"><div class="stat-val">{{ $goals->where('progress', 100)->count() }}</div><div class="stat-lbl">Completed</div></div></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-8">
          <div class="section-label">Your Goals</div>
          @if($goals->isEmpty())
          <div class="empty-state shadow-sm">
            <div class="empty-icon">🎯</div>
            <div class="empty-title">No goals set yet</div>
            <div class="empty-sub">Set your first goal to start tracking<br>your weekly & monthly progress.</div>
            <a href="{{ route('goal.create') }}" class="btn fw-semibold px-4" style="background:var(--purple);color:white;border-radius:10px;font-size:13px;border:none;">🎯 Set First Goal</a>
          </div>
          @else
            @php
              $catColors=['reflection'=>['color'=>'#8B6BAE','bg'=>'var(--purple-pale)','border'=>'var(--purple)'],'exercise'=>['color'=>'#7A9E7E','bg'=>'var(--sage-pale)','border'=>'var(--sage)'],'reading'=>['color'=>'#C8863A','bg'=>'var(--amber-pale)','border'=>'var(--amber)'],'study'=>['color'=>'#5B7FA6','bg'=>'var(--blue-pale)','border'=>'var(--blue)'],'other'=>['color'=>'#8C8680','bg'=>'#F1EFE8','border'=>'#8C8680']];
              $catIcons=['reflection'=>'✍️','exercise'=>'🏃','reading'=>'📚','study'=>'💻','other'=>'🎵'];
            @endphp
            @foreach($goals as $goal)
            @php $c=$catColors[$goal->category]??$catColors['other']; $progress=$goal->progress; @endphp
            <div class="goal-card shadow-sm" style="border-left-color:{{ $c['border'] }}">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <div class="goal-title">{{ $catIcons[$goal->category]??'🎯' }} {{ $goal->title }}</div>
                  <div class="goal-meta">Target: {{ $goal->target }} {{ $goal->unit }} · {{ ucfirst($goal->period) }} · Progress: {{ $goal->current }} / {{ $goal->target }}</div>
                </div>
                <div class="goal-pct" style="color:{{ $c['color'] }}">{{ $progress }}%</div>
              </div>
              <div class="prog-bg"><div class="prog-fill" style="width:{{ $progress }}%;background:{{ $c['color'] }}"></div></div>
              <div class="goal-footer">
                <span style="color:{{ $c['color'] }};font-weight:600;">
                  @if($progress>=100) ✅ Completed! @elseif($progress>=70) 📈 On track @elseif($progress>=40) 💪 Keep going @else ⚠️ Needs more effort @endif
                </span>
                <div class="d-flex align-items-center gap-2">
                  <form method="POST" action="{{ route('goal.update', $goal) }}" class="d-flex align-items-center gap-1">
                    @csrf @method('PATCH')
                    <input type="number" name="current" value="{{ $goal->current }}" min="0" max="{{ $goal->target }}" style="width:56px;padding:3px 6px;border-radius:6px;border:1px solid rgba(28,26,23,.15);font-size:12px;text-align:center;font-family:'DM Sans',sans-serif;">
                    <span style="font-size:11px;color:var(--ink-muted)">/ {{ $goal->target }}</span>
                    <button type="submit" style="background:{{ $c['bg'] }};color:{{ $c['color'] }};border:1px solid {{ $c['border'] }};border-radius:6px;font-size:11px;font-weight:600;padding:3px 8px;cursor:pointer;font-family:'DM Sans',sans-serif;">Update</button>
                  </form>
                  <form method="POST" action="{{ route('goal.destroy', $goal) }}" onsubmit="return confirm('Delete this goal?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:none;border:none;font-size:12px;color:var(--ink-muted);cursor:pointer;padding:0;">🗑</button>
                  </form>
                </div>
              </div>
              @if($goal->note)<div style="font-size:12px;color:var(--ink-muted);margin-top:8px;padding-top:8px;border-top:1px solid rgba(28,26,23,.06);">{{ $goal->note }}</div>@endif
            </div>
            @endforeach
            <a href="{{ route('goal.create') }}" class="btn fw-semibold mt-2" style="background:var(--purple-pale);color:var(--purple);border-radius:10px;font-size:13px;border:none;">+ Add Another Goal</a>
          @endif
        </div>

        <div class="col-4">
          <div class="section-label" style="margin-top:0">Achievement Summary</div>
          <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            @if($goals->isEmpty())
            <div class="text-center py-3"><div style="font-size:32px;opacity:.3;margin-bottom:10px;">📊</div><div style="font-size:13px;color:var(--ink-muted);">Set goals to see your achievement summary here.</div></div>
            @else
              @foreach($goals as $goal)
              @php $c=$catColors[$goal->category]??$catColors['other']; @endphp
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-1" style="font-size:13px;">
                  <span>{{ $catIcons[$goal->category]??'🎯' }} {{ Str::limit($goal->title, 20) }}</span>
                  <span style="font-weight:700;color:{{ $c['color'] }}">{{ $goal->progress }}%</span>
                </div>
                <div class="prog-bg"><div class="prog-fill" style="width:{{ $goal->progress }}%;background:{{ $c['color'] }}"></div></div>
              </div>
              @endforeach
            @endif
          </div>

          <div class="section-label">Goal Setting Tips 💡</div>
          <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex flex-column gap-3" style="font-size:13px;color:var(--ink-muted);line-height:1.65;">
              <div class="d-flex gap-2"><span style="color:var(--purple);">🎯</span><span>Set specific, measurable goals with clear target numbers.</span></div>
              <div class="d-flex gap-2"><span style="color:var(--sage);">📅</span><span>Weekly goals are easier to maintain than monthly ones.</span></div>
              <div class="d-flex gap-2"><span style="color:var(--amber);">🔥</span><span>Consistency beats intensity — small steps every day wins.</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
