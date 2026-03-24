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
    .sidebar {
      width:var(--sidebar-w); background:var(--ink);
      min-height:100vh; position:fixed; top:0; left:0;
      display:flex; flex-direction:column; padding:28px 0 24px; z-index:100;
    }
    .sidebar-logo { padding:0 18px 24px; border-bottom:1px solid rgba(255,255,255,.07); margin-bottom:16px; display:flex; align-items:center; gap:10px; }
    .logo-icon { width:40px; height:40px; background:linear-gradient(135deg,var(--amber),#D4956A); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:18px; }
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
    .topbar-btn { width:36px; height:36px; border-radius:50%; background:white; border:1px solid rgba(28,26,23,.1); display:flex; align-items:center; justify-content:center; font-size:15px; }
    .hero-card { background:linear-gradient(140deg,#2A2420 0%,#3D3228 60%,#4A3C2E 100%); border-radius:20px; padding:36px; position:relative; overflow:hidden; color:white; }
    .hero-card::before { content:""; position:absolute; width:300px; height:300px; background:radial-gradient(circle,rgba(200,134,58,.2) 0%,transparent 70%); top:-80px; right:-60px; border-radius:50%; }
    .hero-eyebrow { font-size:11px; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:var(--amber-light); margin-bottom:8px; }
    .hero-title { font-family:'DM Serif Display',serif; font-size:32px; }
    .kpi-card { background:white; border-radius:16px; padding:22px 24px; }
    .kpi-label { font-size:11px; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:12px; }
    .kpi-empty-val { font-family:'DM Serif Display',serif; font-size:34px; color:#D3D1C7; line-height:1; margin-bottom:6px; }
    .kpi-empty-hint { font-size:12px; color:#B4B2A9; }
    .empty-state { background:white; border-radius:16px; padding:40px 24px; text-align:center; }
    .empty-icon { font-size:40px; margin-bottom:14px; opacity:.4; }
    .empty-title { font-size:15px; font-weight:600; color:var(--ink); margin-bottom:6px; }
    .empty-sub { font-size:13px; color:var(--ink-muted); line-height:1.6; margin-bottom:20px; }
    .quick-card { border-radius:14px; padding:18px; cursor:pointer; transition:transform .15s; }
    .quick-card:hover { transform:translateY(-2px); }
    .qc-icon { font-size:24px; margin-bottom:8px; }
    .qc-title { font-size:14px; font-weight:700; }
    .qc-sub { font-size:11px; opacity:.65; margin-top:2px; }
    .mood-circle-empty { width:48px; height:48px; border-radius:50%; margin:0 auto 6px; background:#F1EFE8; border:2px dashed #D3D1C7; }
    .mood-today { width:48px; height:48px; border-radius:50%; margin:0 auto 6px; background:var(--blue-pale); border:2px dashed var(--blue); display:flex; align-items:center; justify-content:center; font-size:18px; }
    .mood-lbl { font-size:11px; color:var(--ink-muted); font-weight:500; text-align:center; }
    .prog-bg { height:6px; background:var(--cream); border-radius:3px; }
    .section-label { font-size:11px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:12px; margin-top:24px; }
    .section-label:first-child { margin-top:0; }
    .getstarted-banner { background:linear-gradient(135deg,var(--amber-pale),#FDF3E3); border:1.5px solid rgba(200,134,58,.25); border-radius:16px; padding:20px 24px; }
  </style>
</head>
<body>

  <div class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon">📔</div>
      <div>
        <div class="logo-name">Memo Diary</div>
        <div class="logo-tagline">SELF-GROWTH LOG</div>
      </div>
    </div>
    <div class="nav-section-label">Main</div>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link active" href="{{ url('/dashboard') }}"><span>🏠</span> Home</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><span>✍️</span> Daily Reflection</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><span>🏃</span> Activity Log</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><span>🎯</span> Goal Tracking</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><span>📊</span> Analytics</a></li>
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
      <div class="ms-auto d-flex align-items-center gap-3">
        <div class="topbar-btn">🔔</div>
        <div class="topbar-btn">⚙️</div>
      </div>
    </div>

    <div class="p-4">

      <div class="hero-card mb-4">
        <div class="row align-items-center position-relative">
          <div class="col-8">
            <div class="hero-eyebrow">Welcome</div>
            <div class="hero-title mb-2">Hello, {{ auth()->user()->name }} 👋</div>
            <div style="font-size:14px;color:rgba(255,255,255,.45);" class="mb-4">Your journey starts here. Record your first reflection today!</div>
            <div class="d-flex gap-2">
              <button class="btn text-white fw-semibold" style="background:var(--amber);border-radius:10px;font-size:13px;">✍️ Write First Reflection</button>
              <button class="btn fw-semibold" style="background:rgba(255,255,255,.1);color:white;border:1px solid rgba(255,255,255,.2);border-radius:10px;font-size:13px;">🏃 Log an Activity</button>
            </div>
          </div>
          <div class="col-4 text-end">
            <div style="font-size:11px;color:rgba(255,255,255,.35);letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px;">Weekly Growth Score</div>
            <div style="font-family:'DM Serif Display',serif;font-size:72px;line-height:1;color:rgba(255,255,255,.15);">--</div>
            <div style="font-size:12px;color:rgba(255,255,255,.3);margin-top:6px;">No data yet · Start logging!</div>
          </div>
        </div>
      </div>

      <div class="getstarted-banner mb-4 d-flex align-items-center gap-3">
        <span style="font-size:28px;">🚀</span>
        <div class="flex-grow-1">
          <div style="font-size:14px;font-weight:700;color:var(--ink);">Get started with Memo Diary</div>
          <div style="font-size:12px;color:var(--ink-muted);margin-top:2px;">Complete the steps below to set up your growth journey.</div>
        </div>
        <div style="font-size:13px;font-weight:600;color:var(--amber);">0 / 3 done</div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-3"><div class="kpi-card shadow-sm" style="border-top:3px solid #D3D1C7"><div class="kpi-label">Days Logged</div><div class="kpi-empty-val">0</div><div class="kpi-empty-hint">Start your first log →</div></div></div>
        <div class="col-3"><div class="kpi-card shadow-sm" style="border-top:3px solid #D3D1C7"><div class="kpi-label">This Month's Exercise</div><div class="kpi-empty-val">0</div><div class="kpi-empty-hint">No activity logged yet</div></div></div>
        <div class="col-3"><div class="kpi-card shadow-sm" style="border-top:3px solid #D3D1C7"><div class="kpi-label">Avg Mood Score</div><div class="kpi-empty-val">--</div><div class="kpi-empty-hint">Log mood to see score</div></div></div>
        <div class="col-3"><div class="kpi-card shadow-sm" style="border-top:3px solid #D3D1C7"><div class="kpi-label">Goal Achievement Rate</div><div class="kpi-empty-val">--%</div><div class="kpi-empty-hint">Set your first goal →</div></div></div>
      </div>

      <div class="row g-4">
        <div class="col-8">
          <div class="section-label">This Week's Mood Trend</div>
          <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
            <div class="d-flex gap-2 justify-content-between">
              @foreach(['Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
              <div class="text-center flex-fill"><div class="mood-circle-empty"></div><div class="mood-lbl">{{ $day }}</div></div>
              @endforeach
              <div class="text-center flex-fill"><div class="mood-today">＋</div><div class="mood-lbl" style="color:var(--blue);font-weight:700">Today</div></div>
            </div>
            <div class="text-center mt-3" style="font-size:12px;color:var(--ink-muted);">Log your mood each day to see your weekly trend here.</div>
          </div>
          <div class="section-label">Recent Reflections</div>
          <div class="empty-state shadow-sm">
            <div class="empty-icon">✍️</div>
            <div class="empty-title">No reflections yet</div>
            <div class="empty-sub">Write your first reflection to start tracking<br>your thoughts and growth journey.</div>
            <button class="btn text-white fw-semibold px-4" style="background:var(--amber);border-radius:10px;font-size:13px;">✍️ Write Today's Reflection</button>
          </div>
        </div>

        <div class="col-4">
          <div class="section-label" style="margin-top:0">Quick Actions</div>
          <div class="row g-2 mb-4">
            <div class="col-6"><div class="quick-card" style="background:var(--ink);color:white"><div class="qc-icon">✍️</div><div class="qc-title">Write Reflection</div><div class="qc-sub">Today's Log</div></div></div>
            <div class="col-6"><div class="quick-card" style="background:var(--sage-pale);color:var(--sage)"><div class="qc-icon">🏃</div><div class="qc-title">Activity Log</div><div class="qc-sub">Exercise & Study</div></div></div>
            <div class="col-6"><div class="quick-card" style="background:var(--purple-pale);color:var(--purple)"><div class="qc-icon">🎯</div><div class="qc-title">Set Goals</div><div class="qc-sub">Monthly Progress</div></div></div>
            <div class="col-6"><div class="quick-card" style="background:var(--amber-pale);color:var(--amber)"><div class="qc-icon">✨</div><div class="qc-title">AI Report</div><div class="qc-sub">Available after 7 logs</div></div></div>
          </div>
          <div class="section-label">This Month's Goals</div>
          <div class="empty-state shadow-sm">
            <div class="empty-icon">🎯</div>
            <div class="empty-title">No goals set yet</div>
            <div class="empty-sub">Set your first monthly goal<br>to start tracking progress.</div>
            <button class="btn fw-semibold px-4" style="background:var(--purple-pale);color:var(--purple);border-radius:10px;font-size:13px;border:none;">🎯 Set First Goal</button>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
