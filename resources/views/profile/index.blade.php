<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile — Memo Diary</title>
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
      <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}"><span>🏠</span> Home</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('reflection.create') }}"><span>✍️</span> Daily Reflection</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('activity.create') }}"><span>🏃</span> Activity Log</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('goal.index') }}"><span>🎯</span> Goal Tracking</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('analytics') }}"><span>📊</span> Analytics</a></li>
    </ul>
    <div class="nav-section-label">Personal</div>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link active" href="{{ route('profile') }}"><span>👤</span> Profile</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><span>✨</span> AI Report</a></li>
    </ul>
    <div class="sidebar-footer">
      <div class="d-flex align-items-center gap-2">
        @if($user->avatar)
          <img src="{{ Storage::url($user->avatar) }}" class="user-avatar user-avatar-img">
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
      <span class="topbar-current">Profile</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">

      @if(session('success'))
      <div class="alert d-flex align-items-center gap-2 mb-3 rounded-3 alert-success-custom">
        <span>✅</span><span>{{ session('success') }}</span>
      </div>
      @endif

      @if($errors->any())
      <div class="alert d-flex align-items-center gap-2 mb-3 rounded-3 alert-error-custom">
        <span>❌</span>
        <ul class="mb-0 ps-3">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      {{-- Profile Banner --}}
      <div class="profile-banner">
        <div class="d-flex align-items-center gap-4 position-relative mb-4">
          <div class="avatar-wrapper">
            @if($user->avatar)
              <img src="{{ Storage::url($user->avatar) }}" class="avatar-img">
            @else
              <div class="profile-avatar-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif
            <label for="avatarInputBanner" class="avatar-overlay">
              <div class="avatar-overlay-text">📷<br>Change</div>
            </label>
          </div>
          <div class="flex-grow-1">
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-meta">{{ $user->email }} · Member since {{ $user->created_at->format('F Y') }}</div>
          </div>
          <button class="profile-edit-btn"
                  onclick="document.getElementById('edit-form').scrollIntoView({behavior:'smooth'})">
            ✏️ Edit Profile
          </button>
        </div>
        <div class="row g-2 position-relative">
          <div class="col-3">
            <div class="profile-stat-badge">
              <div class="profile-stat-val">{{ $totalDays }}</div>
              <div class="profile-stat-lbl">Days Logged</div>
            </div>
          </div>
          <div class="col-3">
            <div class="profile-stat-badge">
              <div class="profile-stat-val">{{ $totalActs }}</div>
              <div class="profile-stat-lbl">Activities</div>
            </div>
          </div>
          <div class="col-3">
            <div class="profile-stat-badge">
              <div class="profile-stat-val">{{ $avgMood ? number_format($avgMood, 1) : '--' }}</div>
              <div class="profile-stat-lbl">Avg Mood</div>
            </div>
          </div>
          <div class="col-3">
            <div class="profile-stat-badge">
              <div class="profile-stat-val">{{ $totalGoals }}</div>
              <div class="profile-stat-lbl">Goals Set</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Center Content --}}
      <div class="row justify-content-center">
        <div class="col-8">

          {{-- Account Information --}}
          <div class="section-label">Account Information</div>
          <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="info-row">
              <span class="profile-info-label">Name</span>
              <span class="profile-info-value">{{ $user->name }}</span>
            </div>
            <div class="info-row">
              <span class="profile-info-label">Email</span>
              <span class="profile-info-value">{{ $user->email }}</span>
            </div>
            <div class="info-row">
              <span class="profile-info-label">Member Since</span>
              <span class="profile-info-value">{{ $user->created_at->format('F j, Y') }}</span>
            </div>
            <div class="info-row">
              <span class="profile-info-label">Total Reflections</span>
              <span class="profile-info-value">{{ $totalDays }} days</span>
            </div>
            <div class="info-row">
              <span class="profile-info-label">Total Activities</span>
              <span class="profile-info-value">{{ $totalActs }} logged</span>
            </div>
            <div class="info-row">
              <span class="profile-info-label">Account Status</span>
              <span class="profile-status-badge">Active</span>
            </div>
          </div>

          {{-- Edit Profile Form --}}
          <div class="section-label" id="edit-form">Edit Profile</div>
          <div class="card border-0 shadow-sm rounded-4 p-4">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
              @csrf @method('PUT')

              {{-- Avatar Upload --}}
              <div class="mb-4">
                <label class="form-label-custom">Profile Icon</label>
                <div class="d-flex align-items-center gap-3">
                  @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}"
                         class="avatar-img-sm" id="avatarPreview">
                  @else
                    <div class="profile-avatar-lg avatar-fallback-sm" id="avatarFallback">
                      {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <img src="" class="avatar-img-sm d-none" id="avatarPreview">
                  @endif
                  <div>
                    <label for="avatarInput" class="btn-save btn-label">
                      📷 Choose Image
                    </label>
                    <input type="file" name="avatar" id="avatarInput"
                           class="avatar-input" accept="image/*">
                    <input type="file" name="avatar" id="avatarInputBanner"
                           class="avatar-input" accept="image/*">
                    <div class="goal-form-hint mt-1">JPG, PNG, GIF, WEBP · Max 2MB</div>
                  </div>
                </div>
                @error('avatar')<div class="error-msg mt-1">{{ $message }}</div>@enderror
              </div>

              <div class="mb-3">
                <label class="form-label-custom">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="form-input-custom" required>
                @error('name')<div class="error-msg mt-1">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label class="form-label-custom">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="form-input-custom" required>
                @error('email')<div class="error-msg mt-1">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label class="form-label-custom">
                  New Password <span class="optional-hint">(leave blank to keep current)</span>
                </label>
                <input type="password" name="password" class="form-input-custom" placeholder="••••••••">
                @error('password')<div class="error-msg mt-1">{{ $message }}</div>@enderror
              </div>
              <div class="mb-4">
                <label class="form-label-custom">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-input-custom" placeholder="••••••••">
              </div>
              <div class="d-flex gap-2">
                <button type="submit" class="btn-save">Save Changes</button>
                <button type="reset" class="btn-cancel-sm">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/profile.js') }}"></script>
</body>
</html>
