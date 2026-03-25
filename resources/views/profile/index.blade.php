<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile — Memo Diary</title>
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

    /* ── Sidebar ── */
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

    /* ── Main ── */
    .main-content { margin-left:var(--sidebar-w); min-height:100vh; }
    .topbar { height:60px; background:rgba(253,250,244,.9); backdrop-filter:blur(16px); border-bottom:1px solid rgba(28,26,23,.07); position:sticky; top:0; z-index:50; }
    .topbar-btn { width:36px; height:36px; border-radius:50%; background:white; border:1px solid rgba(28,26,23,.1); display:flex; align-items:center; justify-content:center; font-size:15px; }

    /* ── Profile Banner ── */
    .profile-banner {
      background:linear-gradient(140deg,#2A2420 0%,#3D3228 60%,#4A3C2E 100%);
      border-radius:20px; padding:36px; position:relative; overflow:hidden; color:white; margin-bottom:24px;
    }
    .profile-banner::before { content:""; position:absolute; width:300px; height:300px; background:radial-gradient(circle,rgba(200,134,58,.2) 0%,transparent 70%); top:-80px; right:-60px; border-radius:50%; }
    .profile-avatar-lg {
      width:80px; height:80px; border-radius:50%;
      background:linear-gradient(135deg,var(--amber),var(--rose));
      display:flex; align-items:center; justify-content:center;
      font-family:'DM Serif Display',serif; font-size:32px; color:white; flex-shrink:0;
    }
    .profile-name { font-family:'DM Serif Display',serif; font-size:28px; color:white; margin-bottom:4px; }
    .profile-meta { font-size:13px; color:rgba(255,255,255,.45); }
    .stat-badge { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); border-radius:12px; padding:14px 24px; text-align:center; }
    .stat-val { font-family:'DM Serif Display',serif; font-size:28px; color:white; line-height:1; margin-bottom:4px; }
    .stat-lbl { font-size:11px; color:rgba(255,255,255,.4); letter-spacing:.08em; text-transform:uppercase; }

    /* ── Cards ── */
    .section-label { font-size:11px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:12px; margin-top:24px; }
    .section-label:first-child { margin-top:0; }
    .info-row { display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid rgba(28,26,23,.06); font-size:14px; }
    .info-row:last-child { border-bottom:none; }
    .info-label { color:var(--ink-muted); font-size:12px; font-weight:500; }
    .info-value { color:var(--ink); font-weight:500; }

    /* ── Goal cards ── */
    .goal-card { background:white; border-radius:14px; padding:18px 20px; margin-bottom:12px; }
    .prog-bg { height:6px; background:var(--cream); border-radius:3px; }
    .prog-fill { height:6px; border-radius:3px; }

    /* ── Edit form ── */
    .form-label-custom { font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:6px; display:block; }
    .form-input-custom { width:100%; padding:11px 14px; border-radius:10px; border:1.5px solid rgba(28,26,23,.1); background:white; font-family:'DM Sans',sans-serif; font-size:14px; color:var(--ink); outline:none; transition:border-color .2s; }
    .form-input-custom:focus { border-color:var(--amber); }
    .btn-save { background:var(--amber); color:white; border:none; border-radius:10px; padding:10px 28px; font-size:13px; font-weight:600; cursor:pointer; transition:.18s; font-family:'DM Sans',sans-serif; }
    .btn-save:hover { background:#B8762A; transform:translateY(-1px); }
    .btn-cancel { background:transparent; color:var(--ink-muted); border:1.5px solid rgba(28,26,23,.15); border-radius:10px; padding:10px 20px; font-size:13px; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif; }
  </style>
</head>
<body>

  {{-- ════ SIDEBAR ════ --}}
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
      <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}"><span>🏠</span> Home</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><span>✍️</span> Daily Reflection</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><span>🏃</span> Activity Log</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><span>🎯</span> Goal Tracking</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><span>📊</span> Analytics</a></li>
    </ul>
    <div class="nav-section-label">Personal</div>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link active" href="{{ route('profile') }}"><span>👤</span> Profile</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><span>✨</span> AI Report</a></li>
    </ul>
    <div class="sidebar-footer">
      <div class="d-flex align-items-center gap-2">
        <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div>
          <div style="font-size:12.5px;font-weight:600;color:rgba(255,255,255,.8);">{{ $user->name }}</div>
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

    {{-- Topbar --}}
    <div class="topbar d-flex align-items-center px-4">
      <span class="text-muted" style="font-size:12px;">Memo Diary</span>
      <span class="text-muted mx-2" style="opacity:.4;">›</span>
      <span style="font-size:15px;font-weight:600;color:var(--ink);">Profile</span>
      <div class="ms-auto d-flex align-items-center gap-3">
        <div class="topbar-btn">🔔</div>
        <div class="topbar-btn">⚙️</div>
      </div>
    </div>

    <div class="p-4">

      {{-- Profile Banner --}}
      <div class="profile-banner">
        <div class="d-flex align-items-center gap-4 position-relative mb-4">
          <div class="profile-avatar-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
          <div>
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-meta">{{ $user->email }} · Member since {{ $user->created_at->format('F Y') }}</div>
          </div>
          <div class="ms-auto">
            <button class="btn text-white fw-semibold"
                    style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:10px;font-size:13px;">
              ✏️ Edit Profile
            </button>
          </div>
        </div>
        {{-- Stats --}}
        <div class="row g-3 position-relative">
          <div class="col-3">
            <div class="stat-badge">
              <div class="stat-val">0</div>
              <div class="stat-lbl">Days Logged</div>
            </div>
          </div>
          <div class="col-3">
            <div class="stat-badge">
              <div class="stat-val">0</div>
              <div class="stat-lbl">Day Streak</div>
            </div>
          </div>
          <div class="col-3">
            <div class="stat-badge">
              <div class="stat-val">--</div>
              <div class="stat-lbl">Growth Score</div>
            </div>
          </div>
          <div class="col-3">
            <div class="stat-badge">
              <div class="stat-val">0</div>
              <div class="stat-lbl">Goals Set</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4">

        {{-- Left: Account Info + Edit Form --}}
        <div class="col-7">

          {{-- Account Info --}}
          <div class="section-label">Account Information</div>
          <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="info-row">
              <span class="info-label">Name</span>
              <span class="info-value">{{ $user->name }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Email</span>
              <span class="info-value">{{ $user->email }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Member Since</span>
              <span class="info-value">{{ $user->created_at->format('F j, Y') }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Account Status</span>
              <span class="badge rounded-pill px-3 py-1" style="background:var(--sage-pale);color:var(--sage);font-size:12px;">Active</span>
            </div>
          </div>

          {{-- Edit Profile Form --}}
          <div class="section-label">Edit Profile</div>
          <div class="card border-0 shadow-sm rounded-4 p-4">

            @if (session('success'))
              <div class="alert mb-3 rounded-3" style="background:#F0FDF4;border:1px solid #BBF7D0;color:#166534;font-size:13px;">
                ✅ {{ session('success') }}
              </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label class="form-label-custom">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="form-input-custom" required>
                @error('name')<div style="font-size:12px;color:var(--rose);margin-top:4px;">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label class="form-label-custom">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="form-input-custom" required>
                @error('email')<div style="font-size:12px;color:var(--rose);margin-top:4px;">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label class="form-label-custom">New Password <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#B4B2A9;">(leave blank to keep current)</span></label>
                <input type="password" name="password" class="form-input-custom" placeholder="••••••••">
                @error('password')<div style="font-size:12px;color:var(--rose);margin-top:4px;">{{ $message }}</div>@enderror
              </div>
              <div class="mb-4">
                <label class="form-label-custom">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-input-custom" placeholder="••••••••">
              </div>
              <div class="d-flex gap-2">
                <button type="submit" class="btn-save">Save Changes</button>
                <button type="reset" class="btn-cancel">Cancel</button>
              </div>
            </form>
          </div>

        </div>

        {{-- Right: Goals + Growth --}}
        <div class="col-5">

          {{-- Growth Score --}}
          <div class="section-label" style="margin-top:0">Growth Score</div>
          <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-center"
               style="background:linear-gradient(135deg,#2A2420,#3D3228);color:white;">
            <div style="font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.35);margin-bottom:8px;">Current Score</div>
            <div style="font-family:'DM Serif Display',serif;font-size:64px;line-height:1;color:rgba(255,255,255,.2);">--</div>
            <div style="font-size:12px;color:rgba(255,255,255,.3);margin-top:8px;">Start logging to see your score</div>
          </div>

          {{-- Monthly Goals --}}
          <div class="section-label">This Month's Goals</div>
          <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="text-center py-3">
              <div style="font-size:32px;opacity:.3;margin-bottom:10px;">🎯</div>
              <div style="font-size:14px;font-weight:600;color:var(--ink);margin-bottom:6px;">No goals set yet</div>
              <div style="font-size:12px;color:var(--ink-muted);margin-bottom:16px;">Set your first goal to start tracking progress</div>
              <button class="btn fw-semibold px-4"
                      style="background:var(--purple-pale);color:var(--purple);border-radius:10px;font-size:13px;border:none;">
                🎯 Set First Goal
              </button>
            </div>
          </div>

          {{-- Preferences --}}
          <div class="section-label">Preferences</div>
          <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="info-row">
              <span class="info-label">Language</span>
              <span class="info-value" style="font-size:13px;">English</span>
            </div>
            <div class="info-row">
              <span class="info-label">Notifications</span>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" checked style="cursor:pointer;accent-color:var(--amber);">
              </div>
            </div>
            <div class="info-row">
              <span class="info-label">Theme</span>
              <span class="info-value" style="font-size:13px;">Warm Light</span>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
