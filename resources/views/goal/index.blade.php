<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Goal Tracking — Memo Diary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/memo-diary.css') }}" rel="stylesheet">
  <link href="{{ asset('css/goal.css') }}" rel="stylesheet">
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
      <span class="topbar-current">Goal Tracking</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">

      @if(session('success'))
      <div class="alert d-flex align-items-center gap-2 mb-3 rounded-3 alert-success-custom">
        <span>✅</span><span>{{ session('success') }}</span>
      </div>
      @endif

      {{-- Hero --}}
      <div class="hero-card hero-card-purple">
        <div class="row align-items-center position-relative">
          <div class="col-7">
            <div class="hero-eyebrow hero-eyebrow-purple">Goals</div>
            <div class="hero-title mb-2">Goal Tracker 🎯</div>
            <div class="hero-sub mb-4">Set weekly & monthly goals and track your achievement rates.</div>
            <a href="{{ route('goal.create') }}" class="btn-purple">+ Set New Goal</a>
          </div>
          <div class="col-5">
            <div class="d-flex gap-2">
              <div class="stat-badge flex-fill">
                <div class="stat-val">{{ $goals->count() }}</div>
                <div class="stat-lbl">In Progress</div>
              </div>
              <div class="stat-badge flex-fill">
                <div class="stat-val">{{ $goals->count() > 0 ? round($goals->avg(fn($g) => $g->progress)) : '--' }}%</div>
                <div class="stat-lbl">Avg Rate</div>
              </div>
              <div class="stat-badge flex-fill">
                <div class="stat-val">{{ $goals->where('progress', 100)->count() }}</div>
                <div class="stat-lbl">Completed</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4">

        {{-- ── Left: Goal List ── --}}
        <div class="col-8">
          <div class="section-label">Your Goals</div>

          @if($goals->isEmpty())
          <div class="empty-state shadow-sm">
            <div class="empty-icon-lg">🎯</div>
            <div class="empty-title-lg">No goals set yet</div>
            <div class="empty-sub-lg">Set your first goal to start tracking<br>your weekly & monthly progress.</div>
            <a href="{{ route('goal.create') }}" class="btn-purple">🎯 Set First Goal</a>
          </div>

          @else
            @php
              $catColors = [
                'reflection' => ['color'=>'#8B6BAE','bg'=>'var(--purple-pale)','border'=>'var(--purple)'],
                'exercise'   => ['color'=>'#7A9E7E','bg'=>'var(--sage-pale)',  'border'=>'var(--sage)'],
                'reading'    => ['color'=>'#C8863A','bg'=>'var(--amber-pale)', 'border'=>'var(--amber)'],
                'study'      => ['color'=>'#5B7FA6','bg'=>'var(--blue-pale)',  'border'=>'var(--blue)'],
                'other'      => ['color'=>'#8C8680','bg'=>'#F1EFE8',           'border'=>'#8C8680'],
              ];
              $catIcons = [
                'reflection'=>'✍️','exercise'=>'🏃','reading'=>'📚','study'=>'💻','other'=>'🎵'
              ];
            @endphp

            @foreach($goals as $goal)
            @php
              $c        = $catColors[$goal->category] ?? $catColors['other'];
              $progress = $goal->progress;
            @endphp
            <div class="goal-card shadow-sm" style="border-left-color:{{ $c['border'] }}">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                  <div class="goal-title">{{ $catIcons[$goal->category] ?? '🎯' }} {{ $goal->title }}</div>
                  <div class="goal-meta">Target: {{ $goal->target }} {{ $goal->unit }} · {{ ucfirst($goal->period) }} · Progress: {{ $goal->current }} / {{ $goal->target }}</div>
                </div>
                <div class="goal-pct" style="color:{{ $c['color'] }}">{{ $progress }}%</div>
              </div>
              <div class="prog-bg">
                <div class="prog-fill" style="width:{{ $progress }}%;background:{{ $c['color'] }}"></div>
              </div>
              <div class="goal-footer">
                <span class="
                  @if($progress >= 100) goal-status-completed
                  @elseif($progress >= 70) goal-status-ontrack
                  @elseif($progress >= 40) goal-status-keep
                  @else goal-status-warn
                  @endif">
                  @if($progress >= 100) ✅ Completed!
                  @elseif($progress >= 70) 📈 On track
                  @elseif($progress >= 40) 💪 Keep going
                  @else ⚠️ Needs more effort
                  @endif
                </span>
                <div class="d-flex align-items-center gap-2">
                  <form method="POST" action="{{ route('goal.update', $goal) }}" class="d-flex align-items-center gap-1">
                    @csrf @method('PATCH')
                    <input type="number" name="current" value="{{ $goal->current }}"
                           min="0" max="{{ $goal->target }}"
                           class="goal-update-input">
                    <span class="goal-update-slash">/ {{ $goal->target }}</span>
                    <button type="submit" class="goal-update-btn"
                            style="background:{{ $c['bg'] }};color:{{ $c['color'] }};border-color:{{ $c['border'] }}">
                      Update
                    </button>
                  </form>
                  <form method="POST" action="{{ route('goal.destroy', $goal) }}"
                        onsubmit="return confirm('Delete this goal?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm text-muted p-0 border-0 bg-transparent">🗑</button>
                  </form>
                </div>
              </div>
              @if($goal->note)
              <div class="goal-note">{{ $goal->note }}</div>
              @endif
            </div>
            @endforeach

            <a href="{{ route('goal.create') }}" class="add-goal-btn mt-2 d-inline-block">+ Add Another Goal</a>
          @endif
        </div>

        {{-- ── Right: Summary ── --}}
        <div class="col-4">
          <div class="section-label-notop">Achievement Summary</div>
          <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            @if($goals->isEmpty())
            <div class="summary-empty">
              <div class="summary-empty-icon">📊</div>
              <div class="summary-empty-text">Set goals to see your achievement summary here.</div>
            </div>
            @else
              @foreach($goals as $goal)
              @php $c = $catColors[$goal->category] ?? $catColors['other']; @endphp
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                  <span class="summary-goal-label">{{ $catIcons[$goal->category] ?? '🎯' }} {{ Str::limit($goal->title, 20) }}</span>
                  <span class="goal-summary-val" style="color:{{ $c['color'] }}">{{ $goal->progress }}%</span>
                </div>
                <div class="prog-bg">
                  <div class="prog-fill" style="width:{{ $goal->progress }}%;background:{{ $c['color'] }}"></div>
                </div>
              </div>
              @endforeach
            @endif
          </div>

          <div class="section-label">Goal Setting Tips 💡</div>
          <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex flex-column gap-3">
              <div class="tip-item"><span class="label-purple">🎯</span><span>Set specific, measurable goals with clear target numbers.</span></div>
              <div class="tip-item"><span class="label-sage">📅</span><span>Weekly goals are easier to maintain than monthly ones.</span></div>
              <div class="tip-item"><span class="label-amber">🔥</span><span>Consistency beats intensity — small steps every day wins.</span></div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
