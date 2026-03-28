<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings — Memo Diary</title>
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
    .topbar-btn { width:36px; height:36px; border-radius:50%; background:white; border:1px solid rgba(28,26,23,.1); display:flex; align-items:center; justify-content:center; font-size:15px; text-decoration:none; color:inherit; }
    .settings-tab-btn { display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:10px; border:none; background:transparent; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:500; color:var(--ink-muted); cursor:pointer; transition:.15s; text-align:left; width:100%; }
    .settings-tab-btn:hover { background:var(--cream); color:var(--ink); }
    .settings-tab-btn.active { background:var(--amber-pale); color:var(--amber); font-weight:600; }
    .settings-tab-icon { font-size:16px; width:20px; text-align:center; }
    .form-select-custom { width:100%; padding:11px 14px; border-radius:10px; border:1.5px solid rgba(28,26,23,.1); background:white; font-family:'DM Sans',sans-serif; font-size:14px; color:var(--ink); outline:none; cursor:pointer; }
    .form-select-custom:focus { border-color:var(--amber); }
    .form-input-custom { width:100%; padding:11px 14px; border-radius:10px; border:1.5px solid rgba(28,26,23,.1); background:white; font-family:'DM Sans',sans-serif; font-size:14px; color:var(--ink); outline:none; transition:border-color .2s; }
    .form-input-custom:focus { border-color:var(--amber); }
    .form-label-custom { font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:6px; display:block; }
    .btn-save { background:var(--amber); color:white; border:none; border-radius:10px; padding:10px 28px; font-size:13px; font-weight:600; cursor:pointer; transition:.18s; font-family:'DM Sans',sans-serif; }
    .btn-save:hover { background:#B8762A; transform:translateY(-1px); }
    .btn-danger { background:var(--rose); color:white; border:none; border-radius:10px; padding:10px 28px; font-size:13px; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif; transition:.18s; }
    .btn-danger:hover { background:#B5615A; }
    .toggle-row { display:flex; justify-content:space-between; align-items:center; padding:14px 0; border-bottom:1px solid rgba(28,26,23,.06); }
    .toggle-row:last-child { border-bottom:none; }
    .toggle-label { font-size:14px; color:var(--ink); font-weight:500; }
    .toggle-sub { font-size:12px; color:var(--ink-muted); margin-top:2px; }
    .section-label { font-size:11px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:16px; margin-top:24px; }
    .section-label:first-child { margin-top:0; }
    .settings-hero { background:linear-gradient(140deg,#2A2420 0%,#3D3228 60%,#4A3C2E 100%); border-radius:20px; padding:32px 36px; position:relative; overflow:hidden; color:white; margin-bottom:24px; }
    .settings-hero::before { content:""; position:absolute; width:250px; height:250px; background:radial-gradient(circle,rgba(200,134,58,.2) 0%,transparent 70%); top:-60px; right:-40px; border-radius:50%; }
  </style>
</head>
<body x-data="{ tab: 'notifications' }">

  {{-- ════ SIDEBAR ════ --}}
  <div class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon" style="overflow:hidden;padding:0;background:none;">
        <img src="{{ asset('images/logo.jpg') }}" style="width:40px;height:40px;object-fit:cover;border-radius:12px;display:block;">
      </div>
      <div>
        <div class="logo-name">Memo Diary</div>
        <div class="logo-tagline">SELF-GROWTH LOG</div>
      </div>
    </div>
    <div class="nav-section-label">Main</div>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}"><span>🏠</span> Home</a></li>
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
    <div class="topbar d-flex align-items-center px-4">
      <span class="text-muted" style="font-size:12px;">Memo Diary</span>
      <span class="text-muted mx-2" style="opacity:.4;">›</span>
      <span style="font-size:15px;font-weight:600;color:var(--ink);">Settings</span>
      <div class="ms-auto d-flex align-items-center gap-3">
        <a href="{{ route('settings') }}" class="topbar-btn">⚙️</a>
      </div>
    </div>

    <div class="p-4">

      @if(session('success'))
      <div class="alert d-flex align-items-center gap-2 mb-3 rounded-3"
           style="background:#F0FDF4;border:1px solid #BBF7D0;color:#166534;font-size:13px;padding:12px 16px;">
        <span>✅</span><span>{{ session('success') }}</span>
      </div>
      @endif

      {{-- Hero --}}
      <div class="settings-hero">
        <div class="position-relative">
          <div style="font-size:11px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--amber-light);margin-bottom:8px;">Settings</div>
          <div style="font-family:'DM Serif Display',serif;font-size:28px;color:white;margin-bottom:6px;">App Settings ⚙️</div>
          <div style="font-size:14px;color:rgba(255,255,255,.45);">Manage your notifications, appearance, and privacy.</div>
        </div>
      </div>

      <div class="row g-4">

        {{-- Left Nav --}}
        <div class="col-3">
          <div class="card border-0 shadow-sm rounded-4 p-3">
            <div class="d-flex flex-column gap-1">
              <button class="settings-tab-btn" :class="{ active: tab === 'notifications' }" @click="tab = 'notifications'">
                <span class="settings-tab-icon">🔔</span> Notifications
              </button>
              <button class="settings-tab-btn" :class="{ active: tab === 'appearance' }" @click="tab = 'appearance'">
                <span class="settings-tab-icon">🎨</span> Appearance
              </button>
              <button class="settings-tab-btn" :class="{ active: tab === 'privacy' }" @click="tab = 'privacy'">
                <span class="settings-tab-icon">🔒</span> Privacy
              </button>
              <button class="settings-tab-btn" :class="{ active: tab === 'danger' }" @click="tab = 'danger'">
                <span class="settings-tab-icon">⚠️</span> Danger Zone
              </button>
            </div>
          </div>
        </div>

        {{-- Right Content --}}
        <div class="col-9">

          {{-- ── Notifications ── --}}
          <div x-show="tab === 'notifications'"
               x-transition:enter="transition ease-out duration-150"
               x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="card border-0 shadow-sm rounded-4 p-4">
              <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                <input type="hidden" name="section" value="notifications">
                <div class="section-label">Notification Settings</div>
                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Daily Reflection Reminder</div>
                    <div class="toggle-sub">Get reminded to write your daily reflection</div>
                  </div>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="notif_reflection"
                           {{ $user->notif_reflection ? 'checked' : '' }}
                           style="cursor:pointer;width:44px;height:22px;accent-color:var(--amber);">
                  </div>
                </div>
                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Activity Log Reminder</div>
                    <div class="toggle-sub">Remind me to log my daily activities</div>
                  </div>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="notif_activity"
                           {{ $user->notif_activity ? 'checked' : '' }}
                           style="cursor:pointer;width:44px;height:22px;accent-color:var(--amber);">
                  </div>
                </div>
                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Goal Progress Updates</div>
                    <div class="toggle-sub">Weekly summary of your goal progress</div>
                  </div>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="notif_goal"
                           {{ $user->notif_goal ? 'checked' : '' }}
                           style="cursor:pointer;width:44px;height:22px;accent-color:var(--amber);">
                  </div>
                </div>
                <div class="toggle-row">
                  <div>
                    <div class="toggle-label" style="color:var(--ink-muted);">AI Report Ready</div>
                    <div class="toggle-sub">Available after 7 logs are recorded</div>
                  </div>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" disabled
                           style="cursor:not-allowed;width:44px;height:22px;opacity:.35;">
                  </div>
                </div>
                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Streak Alerts</div>
                    <div class="toggle-sub">Alert when your logging streak is at risk</div>
                  </div>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="notif_streak"
                           {{ $user->notif_streak ? 'checked' : '' }}
                           style="cursor:pointer;width:44px;height:22px;accent-color:var(--amber);">
                  </div>
                </div>
                <div class="mt-4">
                  <button type="submit" class="btn-save">Save Notification Settings</button>
                </div>
              </form>
            </div>
          </div>

          {{-- ── Appearance ── --}}
          <div x-show="tab === 'appearance'"
               x-transition:enter="transition ease-out duration-150"
               x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="card border-0 shadow-sm rounded-4 p-4">
              <div class="section-label">Theme</div>
              <div class="row g-3 mb-4">
                @foreach([
                  ['Warm Light','Current theme','#F0EAE0','#C8863A',true],
                  ['Dark Mode','Coming soon','#1C1A17','#C8863A',false],
                  ['Sage Green','Coming soon','#E8F0E4','#7A9E7E',false],
                ] as [$name,$desc,$bg,$accent,$active])
                <div class="col-4">
                  <div style="border:2px solid {{ $active ? 'var(--amber)' : 'rgba(28,26,23,.1)' }};border-radius:12px;overflow:hidden;cursor:pointer;transition:.15s;">
                    <div style="height:56px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;">
                      <div style="width:22px;height:22px;border-radius:50%;background:{{ $accent }};"></div>
                    </div>
                    <div style="padding:10px 12px;background:white;">
                      <div style="font-size:13px;font-weight:600;color:var(--ink);">{{ $name }}</div>
                      <div style="font-size:11px;color:var(--ink-muted);">{{ $desc }}</div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
              <div class="section-label">Language</div>
              <div class="mb-4" style="max-width:300px;">
                <select class="form-select-custom">
                  <option selected>English</option>
                  <option>日本語</option>
                </select>
              </div>
              <div class="section-label">Date Format</div>
              <div class="mb-4" style="max-width:300px;">
                <select class="form-select-custom">
                  <option selected>March 26, 2026</option>
                  <option>2026/03/26</option>
                  <option>26/03/2026</option>
                </select>
              </div>
              <button class="btn-save">Save Appearance Settings</button>
            </div>
          </div>

          {{-- ── Privacy ── --}}
          <div x-show="tab === 'privacy'"
               x-transition:enter="transition ease-out duration-150"
               x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="card border-0 shadow-sm rounded-4 p-4">
              <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                <input type="hidden" name="section" value="privacy">
                <div class="section-label">Privacy Settings</div>
                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Profile Visibility</div>
                    <div class="toggle-sub">Allow others to see your profile</div>
                  </div>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="privacy_profile_visible"
                           {{ $user->privacy_profile_visible ? 'checked' : '' }}
                           style="cursor:pointer;width:44px;height:22px;accent-color:var(--amber);">
                  </div>
                </div>
                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Data Analytics</div>
                    <div class="toggle-sub">Allow anonymized data to improve AI features</div>
                  </div>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="privacy_data_analytics"
                           {{ $user->privacy_data_analytics ? 'checked' : '' }}
                           style="cursor:pointer;width:44px;height:22px;accent-color:var(--amber);">
                  </div>
                </div>
                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Two-Factor Authentication</div>
                    <div class="toggle-sub">Add extra security to your account</div>
                  </div>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="privacy_two_factor"
                           {{ $user->privacy_two_factor ? 'checked' : '' }}
                           style="cursor:pointer;width:44px;height:22px;accent-color:var(--amber);">
                  </div>
                </div>
                <div class="mt-4">
                  <button type="submit" class="btn-save">Save Privacy Settings</button>
                </div>
              </form>
            </div>
          </div>

          {{-- ── Danger Zone ── --}}
          <div x-show="tab === 'danger'"
               x-transition:enter="transition ease-out duration-150"
               x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="card border-0 shadow-sm rounded-4 p-4" style="border:1.5px solid var(--rose-pale) !important;">
              <div class="section-label" style="color:var(--rose);">⚠️ Danger Zone</div>
              <div style="background:var(--rose-pale);border-radius:12px;padding:18px;margin-bottom:16px;">
                <div style="font-size:14px;font-weight:600;color:var(--ink);margin-bottom:4px;">Reset All Data</div>
                <div style="font-size:13px;color:var(--ink-muted);line-height:1.6;margin-bottom:12px;">
                  Delete all your reflections, activities, and goals. This action cannot be undone.
                </div>
                <button class="btn-danger"
                        onclick="return confirm('Are you sure? This will delete all your data permanently.')">
                  🗑 Reset All Data
                </button>
              </div>
              <div style="background:var(--rose-pale);border-radius:12px;padding:18px;">
                <div style="font-size:14px;font-weight:600;color:var(--ink);margin-bottom:4px;">Delete Account</div>
                <div style="font-size:13px;color:var(--ink-muted);line-height:1.6;margin-bottom:12px;">
                  Permanently delete your account and all associated data. This cannot be undone.
                </div>
                <form method="POST" action="{{ route('profile.destroy') }}"
                      onsubmit="return confirm('Are you absolutely sure? Your account will be permanently deleted.')">
                  @csrf @method('DELETE')
                  <div class="mb-3" style="max-width:320px;">
                    <label class="form-label-custom" style="color:var(--rose);">Confirm Password</label>
                    <input type="password" name="password" class="form-input-custom"
                           placeholder="Enter your password to confirm">
                  </div>
                  <button type="submit" class="btn-danger">💀 Delete My Account</button>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
