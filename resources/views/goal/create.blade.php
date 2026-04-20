<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Set New Goal — Memo Diary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/memo-diary.css') }}" rel="stylesheet">
  <script src="{{ asset('js/alpine.min.js') }}" defer></script>
</head>
<body x-data="{ category: '{{ old('category', 'reflection') }}' }">

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
        @if(auth()->user()->avatar)
  <img src="{{ Storage::url(auth()->user()->avatar) }}" class="user-avatar user-avatar-img">
@else
  <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
@endif
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
      <a href="{{ route('goal.index') }}" class="topbar-breadcrumb text-decoration-none">Goal Tracking</a>
      <span class="topbar-sep">›</span>
      <span class="topbar-current">Set New Goal</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">

      {{-- Hero --}}
      <div class="hero-card hero-card-purple">
        <a href="{{ route('goal.index') }}" class="back-link">← Back to Goals</a>
        <div class="hero-eyebrow hero-eyebrow-purple">New Goal</div>
        <div class="hero-title">Set a New Goal 🎯</div>
        <div class="hero-sub">Define your goal clearly to make it achievable and trackable.</div>
      </div>

      <form method="POST" action="{{ route('goal.store') }}">
        @csrf
        <div class="row g-4">

          {{-- ── Left: Form ── --}}
          <div class="col-8">

            {{-- Category --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <label class="form-label-custom">Goal Category</label>
              @error('category')
                <div class="error-msg">{{ $message }}</div>
              @enderror
              <div class="row g-2">
                @foreach([
                  ['reflection','✍️','Reflection'],
                  ['exercise',  '🏃','Exercise'],
                  ['reading',   '📚','Reading'],
                  ['study',     '💻','Study'],
                  ['other',     '🎵','Other'],
                ] as [$val,$icon,$label])
                <div class="col">
                  <div class="cat-card"
                       :class="{ 'cat-card-selected': category === '{{ $val }}' }"
                       @click="category = '{{ $val }}'">
                    <div class="cat-icon">{{ $icon }}</div>
                    <div class="cat-name">{{ $label }}</div>
                  </div>
                </div>
                @endforeach
              </div>
              <input type="hidden" name="category" :value="category">
            </div>

            {{-- Goal Details --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div class="mb-3">
                <label class="form-label-custom">Goal Title</label>
                <input type="text" name="title" class="form-input-custom"
                       placeholder="e.g. Write reflection every day"
                       value="{{ old('title') }}" required>
                @error('title')
                  <div class="error-msg mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="row g-3 mb-3">
                <div class="col-6">
                  <label class="form-label-custom">Period</label>
                  <select name="period" class="form-select-custom">
                    <option value="weekly"  {{ old('period') === 'weekly'  ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ old('period','monthly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                  </select>
                </div>
                <div class="col-3">
                  <label class="form-label-custom">Target</label>
                  <input type="number" name="target" class="form-input-custom"
                         placeholder="30" min="1" value="{{ old('target') }}" required>
                  @error('target')
                    <div class="error-msg mt-1">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-3">
                  <label class="form-label-custom">Unit</label>
                  <input type="text" name="unit" class="form-input-custom"
                         placeholder="days / times" value="{{ old('unit') }}">
                </div>
              </div>

              <div>
                <label class="form-label-custom">
                  Notes <span class="optional-hint">(optional)</span>
                </label>
                <textarea name="note" class="form-textarea-custom form-textarea-sm"
                          placeholder="Why is this goal important to you?">{{ old('note') }}</textarea>
              </div>
            </div>

            {{-- Buttons --}}
            <div class="d-flex gap-3 align-items-center">
              <button type="submit" class="btn-purple">🎯 Save Goal</button>
              <a href="{{ route('goal.index') }}" class="btn-outline">Cancel</a>
            </div>

          </div>

          {{-- ── Right: Tips ── --}}
          <div class="col-4">
            <div class="section-label" style="margin-top:0">Goal Setting Tips 💡</div>
            <div class="card border-0 shadow-sm rounded-4 p-4">
              <div class="d-flex flex-column gap-3">
                <div class="tip-item"><span class="label-purple">🎯</span><span>Set specific, measurable goals with clear numbers.</span></div>
                <div class="tip-item"><span class="label-sage">📅</span><span>Weekly goals are easier to maintain than monthly ones.</span></div>
                <div class="tip-item"><span class="label-amber">🔥</span><span>Consistency beats intensity — small steps every day wins.</span></div>
                <div class="tip-item"><span class="label-blue">📊</span><span>Track progress daily to stay motivated and accountable.</span></div>
              </div>
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>

  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
