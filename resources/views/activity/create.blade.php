<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log Activity — Memo Diary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
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
    .hero-card { background:linear-gradient(140deg,#1E2820 0%,#2D3D2E 100%); border-radius:20px; padding:36px; position:relative; overflow:hidden; color:white; margin-bottom:28px; }
    .hero-card::before { content:""; position:absolute; width:300px; height:300px; background:radial-gradient(circle,rgba(122,158,126,.2) 0%,transparent 70%); top:-80px; right:-60px; border-radius:50%; }
    .hero-eyebrow { font-size:11px; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:#A0D4A5; margin-bottom:8px; position:relative; }
    .hero-title { font-family:'DM Serif Display',serif; font-size:32px; color:white; margin-bottom:6px; position:relative; }
    .hero-sub { font-size:14px; color:rgba(255,255,255,.45); position:relative; }
    .back-link { display:inline-flex; align-items:center; gap:6px; font-size:13px; color:rgba(255,255,255,.5); text-decoration:none; margin-bottom:16px; transition:.18s; position:relative; }
    .back-link:hover { color:white; }
    .activity-type-card { background:white; border-radius:16px; padding:22px 14px; text-align:center; cursor:pointer; border:2px solid transparent; transition:all .18s; }
    .activity-type-card:hover { border-color:var(--sage); transform:translateY(-2px); box-shadow:0 8px 24px rgba(122,158,126,.15); }
    .activity-type-card.selected { border-color:var(--sage); background:var(--sage-pale); }
    .activity-type-icon { font-size:36px; margin-bottom:10px; }
    .activity-type-name { font-size:14px; font-weight:700; color:var(--ink); margin-bottom:3px; }
    .activity-type-sub { font-size:11px; color:var(--ink-muted); }
    .form-label-custom { font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:6px; display:block; }
    .form-input-custom { width:100%; padding:11px 14px; border-radius:10px; border:1.5px solid rgba(28,26,23,.1); background:white; font-family:'DM Sans',sans-serif; font-size:14px; color:var(--ink); outline:none; transition:border-color .2s; }
    .form-input-custom:focus { border-color:var(--sage); }
    .form-textarea-custom { width:100%; padding:11px 14px; border-radius:10px; border:1.5px solid rgba(28,26,23,.1); background:white; font-family:'DM Sans',sans-serif; font-size:14px; color:var(--ink); outline:none; transition:border-color .2s; resize:vertical; min-height:100px; }
    .form-textarea-custom:focus { border-color:var(--sage); }
    .intensity-btn { flex:1; padding:10px; border-radius:10px; border:1.5px solid rgba(28,26,23,.1); background:white; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:500; cursor:pointer; text-align:center; transition:.18s; color:var(--ink-muted); }
    .intensity-btn.selected { border-color:var(--sage); background:var(--sage-pale); color:var(--sage); font-weight:700; }
    .btn-submit { background:var(--sage); color:white; border:none; border-radius:12px; padding:14px 36px; font-size:14px; font-weight:600; cursor:pointer; transition:.18s; font-family:'DM Sans',sans-serif; }
    .btn-submit:hover { background:#6A8E6E; transform:translateY(-1px); }
    .btn-cancel { background:white; color:var(--ink-muted); border:1.5px solid rgba(28,26,23,.12); border-radius:12px; padding:14px 24px; font-size:14px; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif; text-decoration:none; display:inline-block; }
    .info-banner { background:linear-gradient(135deg,#1E2820,#2D3D2E); border-radius:16px; padding:22px 24px; }
    .section-label { font-size:11px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:12px; margin-top:24px; }
    .section-label:first-child { margin-top:0; }
  </style>
</head>
<body x-data="{ selected: '', intensity: '' }">

  <div class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon"><img src="{{ asset('images/logo.jpg') }}" style="width:40px;height:40px;object-fit:cover;border-radius:12px;display:block;"></div>
      <div><div class="logo-name">Memo Diary</div><div class="logo-tagline">SELF-GROWTH LOG</div></div>
    </div>
    <div class="nav-section-label">Main</div>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}"><span>🏠</span> Home</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('reflection.create') }}"><span>✍️</span> Daily Reflection</a></li>
      <li class="nav-item"><a class="nav-link active" href="{{ route('activity.create') }}"><span>🏃</span> Activity Log</a></li>
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
      <span class="text-muted" style="font-size:12px;">Activity Log</span>
      <span class="text-muted mx-2" style="opacity:.4;">›</span>
      <span style="font-size:15px;font-weight:600;color:var(--ink);">Log Activity</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">
      <div class="hero-card">
        <a href="{{ url('/dashboard') }}" class="back-link">← Back to Home</a>
        <div class="hero-eyebrow">Today's Activity</div>
        <div class="hero-title">Log Activity 🏃</div>
        <div class="hero-sub">Select an activity type and enter the details below.</div>
      </div>

      <form method="POST" action="{{ route('activity.store') }}">
        @csrf
        <div class="section-label" style="margin-top:0">Select Activity Type</div>
        @if($errors->has('type'))<div style="font-size:12px;color:var(--rose);margin-bottom:8px;">{{ $errors->first('type') }}</div>@endif
        <div class="row g-3 mb-4">
          @foreach([['🏃','Running','Distance & Pace','running'],['🚶','Walking','Steps & Distance','walking'],['💪','Strength Training','Exercises & Weight','strength'],['🧘','Yoga','Duration & Feeling','yoga'],['📚','Reading','Pages & Thoughts','reading'],['💻','Study','Topic / Hours','study'],['🎵','Other','Free Entry','other']] as [$icon,$name,$sub,$val])
          <div class="col-3">
            <div class="activity-type-card" :class="{ selected: selected === '{{ $val }}' }" @click="selected = '{{ $val }}'">
              <div class="activity-type-icon">{{ $icon }}</div>
              <div class="activity-type-name">{{ $name }}</div>
              <div class="activity-type-sub">{{ $sub }}</div>
            </div>
          </div>
          @endforeach
        </div>
        <input type="hidden" name="type" :value="selected">

        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label-custom">Duration (minutes)</label>
              <input type="number" name="duration" min="1" max="999" class="form-input-custom" placeholder="e.g. 30" value="{{ old('duration') }}">
              @error('duration')<div style="font-size:12px;color:var(--rose);margin-top:4px;">{{ $message }}</div>@enderror
            </div>
            <div class="col-6">
              <label class="form-label-custom">Distance / Amount <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#B4B2A9;">(optional)</span></label>
              <input type="text" name="amount" class="form-input-custom" placeholder="e.g. 5km / 30 pages" value="{{ old('amount') }}">
            </div>
            <div class="col-12">
              <label class="form-label-custom">Intensity</label>
              <div class="d-flex gap-2">
                @foreach(['😌 Easy','💪 Moderate','🔥 Hard','⚡ Max'] as $i => $label)
                <button type="button" class="intensity-btn" :class="{ selected: intensity === '{{ $i+1 }}' }" @click="intensity = '{{ $i+1 }}'">{{ $label }}</button>
                @endforeach
              </div>
              <input type="hidden" name="intensity" :value="intensity">
            </div>
            <div class="col-12">
              <label class="form-label-custom">Notes <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#B4B2A9;">(optional)</span></label>
              <textarea name="note" class="form-textarea-custom" placeholder="How did it go? Any thoughts or feelings...">{{ old('note') }}</textarea>
            </div>
          </div>
        </div>

        <div class="info-banner mb-4 d-flex align-items-center gap-3">
          <span style="font-size:28px;">💡</span>
          <div>
            <div style="font-size:14px;font-weight:700;color:white;margin-bottom:4px;">Daily activity logs power the AI analysis</div>
            <div style="font-size:13px;color:rgba(255,255,255,.5);line-height:1.6;">Activity & mood correlations generate personalized weekly insights just for you.</div>
          </div>
        </div>

        <div class="d-flex gap-3 align-items-center">
          <button type="submit" class="btn-submit">🏃 Save Activity</button>
          <a href="{{ url('/dashboard') }}" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
