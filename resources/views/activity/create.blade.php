<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log Activity — Memo Diary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/memo-diary.css') }}" rel="stylesheet">
  <link href="{{ asset('css/activity.css') }}" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
</head>
<body x-data="{ selected: '', intensity: '' }">

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
          <div class="sidebar-username">{{ auth()->user()->name }}</div>
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
      <span class="topbar-breadcrumb">Activity Log</span>
      <span class="topbar-sep">›</span>
      <span class="topbar-current">Log Activity</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">

      {{-- Hero --}}
      <div class="hero-card hero-card-green">
        <a href="{{ url('/dashboard') }}" class="back-link">← Back to Home</a>
        <div class="hero-eyebrow hero-eyebrow-green">Today's Activity</div>
        <div class="hero-title">Log Activity 🏃</div>
        <div class="hero-sub">Select an activity type and enter the details below.</div>
      </div>

      <form method="POST" action="{{ route('activity.store') }}">
        @csrf

        {{-- Activity Type --}}
        <div class="section-label-notop">Select Activity Type</div>
        @if($errors->has('type'))
          <div class="error-msg">{{ $errors->first('type') }}</div>
        @endif
        <div class="row g-3 mb-4">
          @foreach([
            ['🏃','Running',          'Distance & Pace',    'running'],
            ['🚶','Walking',          'Steps & Distance',   'walking'],
            ['💪','Strength Training','Exercises & Weight', 'strength'],
            ['🧘','Yoga',             'Duration & Feeling', 'yoga'],
            ['📚','Reading',          'Pages & Thoughts',   'reading'],
            ['💻','Study',            'Topic / Hours',      'study'],
            ['🎵','Other',            'Free Entry',         'other'],
          ] as [$icon,$name,$sub,$val])
          <div class="col-3">
            <div class="activity-type-card"
                 :class="{ selected: selected === '{{ $val }}' }"
                 @click="selected = '{{ $val }}'">
              <div class="activity-type-icon">{{ $icon }}</div>
              <div class="activity-type-name">{{ $name }}</div>
              <div class="activity-type-sub">{{ $sub }}</div>
            </div>
          </div>
          @endforeach
        </div>
        <input type="hidden" name="type" :value="selected">

        {{-- Detail Form --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label-custom">Duration (minutes)</label>
              <input type="number" name="duration" min="1" max="999"
                     class="form-input-custom" placeholder="e.g. 30"
                     value="{{ old('duration') }}">
              @error('duration')
                <div class="error-msg mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-6">
              <label class="form-label-custom">
                Distance / Amount <span class="optional-hint">(optional)</span>
              </label>
              <input type="text" name="amount" class="form-input-custom"
                     placeholder="e.g. 5km / 30 pages"
                     value="{{ old('amount') }}">
            </div>
            <div class="col-12">
              <label class="form-label-custom">Intensity</label>
              <div class="d-flex gap-2">
                @foreach(['😌 Easy','💪 Moderate','🔥 Hard','⚡ Max'] as $i => $label)
                <button type="button" class="intensity-btn"
                        :class="{ selected: intensity === '{{ $i + 1 }}' }"
                        @click="intensity = '{{ $i + 1 }}'">
                  {{ $label }}
                </button>
                @endforeach
              </div>
              <input type="hidden" name="intensity" :value="intensity">
            </div>
            <div class="col-12">
              <label class="form-label-custom">
                Notes <span class="optional-hint">(optional)</span>
              </label>
              <textarea name="note" class="form-textarea-custom"
                        placeholder="How did it go? Any thoughts or feelings...">{{ old('note') }}</textarea>
            </div>
          </div>
        </div>

        {{-- Info Banner --}}
        <div class="info-banner mb-4 d-flex align-items-center gap-3">
          <span class="info-banner-icon">💡</span>
          <div>
            <div class="info-banner-title">Daily activity logs power the AI analysis</div>
            <div class="info-banner-sub">Activity & mood correlations generate personalized weekly insights just for you.</div>
          </div>
        </div>

        {{-- Buttons --}}
        <div class="d-flex gap-3 align-items-center">
          <button type="submit" class="btn-sage">🏃 Save Activity</button>
          <a href="{{ url('/dashboard') }}" class="btn-outline">Cancel</a>
        </div>

      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
