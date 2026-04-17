<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings — Memo Diary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/memo-diary.css') }}" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
</head>
<body x-data="{ tab: 'notifications' }">

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
          <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        @endif
        <div>
          <div class="sidebar-username">{{ $user->name }}</div>
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
      <span class="topbar-current">Settings</span>
      <div class="ms-auto">
        <a href="{{ route('settings') }}" class="topbar-btn">⚙️</a>
      </div>
    </div>

    <div class="p-4">

      @if(session('success'))
      <div class="alert d-flex align-items-center gap-2 mb-3 rounded-3 alert-success-custom">
        <span>✅</span><span>{{ session('success') }}</span>
      </div>
      @endif

      {{-- Hero --}}
      <div class="settings-hero">
        <div class="position-relative">
          <div class="hero-eyebrow hero-eyebrow-amber">Settings</div>
          <div class="hero-title">App Settings ⚙️</div>
          <div class="hero-sub">Manage your notifications, appearance, and privacy.</div>
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
          <div x-show="tab === 'notifications'" x-cloak>
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
          <div x-show="tab === 'appearance'" x-cloak>
            <div class="card border-0 shadow-sm rounded-4 p-4">
              <div class="section-label">Theme</div>
              <div class="row g-3 mb-4">
                @foreach([
                  ['Warm Light','Current theme','#F0EAE0','#C8863A',true],
                  ['Dark Mode','Coming soon','#1C1A17','#C8863A',false],
                  ['Sage Green','Coming soon','#E8F0E4','#7A9E7E',false],
                ] as [$name,$desc,$bg,$accent,$active])
                <div class="col-4">
                  <div style="border:2px solid {{ $active ? 'var(--amber)' : 'rgba(28,26,23,.1)' }};border-radius:12px;overflow:hidden;cursor:pointer;">
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
          <div x-show="tab === 'privacy'" x-cloak>
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
          <div x-show="tab === 'danger'" x-cloak>
            <div class="card border-0 shadow-sm rounded-4 p-4" style="border:1.5px solid var(--rose-pale) !important;">
              <div class="section-label" style="color:var(--rose);">⚠️ Danger Zone</div>
              <div style="background:var(--rose-pale);border-radius:12px;padding:18px;margin-bottom:16px;">
                <div style="font-size:14px;font-weight:600;color:var(--ink);margin-bottom:4px;">Reset All Data</div>
                <div style="font-size:13px;color:var(--ink-muted);line-height:1.6;margin-bottom:12px;">
                  Delete all your reflections, activities, and goals. This action cannot be undone.
                </div>
                <button class="btn-rose" onclick="return confirm('Are you sure? This will delete all your data permanently.')">
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
                  <button type="submit" class="btn-rose">💀 Delete My Account</button>
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
