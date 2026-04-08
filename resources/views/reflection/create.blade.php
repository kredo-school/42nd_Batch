<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daily Reflection — Memo Diary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/memo-diary.css') }}" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
</head>
<body x-data="{
  mood: {{ old('mood', 0) }},
  journal: '',
  tags: [],
  get charCount() { return this.journal.length; },
  toggleTag(tag) { if(this.tags.includes(tag)){this.tags=this.tags.filter(t=>t!==tag);}else{this.tags.push(tag);} },
  moodLabels: ['','😞 Very Low','😐 Low','🙂 Okay','😊 Very Good','🤩 Excellent'],
  moodColors: ['','#C4716A','#BA7517','#BA7517','#C8863A','#7A9E7E'],
}">

  {{-- ════ SIDEBAR ════ --}}
  <div class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon">
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
      <li class="nav-item"><a class="nav-link active" href="{{ route('reflection.create') }}"><span>✍️</span> Daily Reflection</a></li>
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
      <span class="topbar-breadcrumb">Daily Reflection</span>
      <span class="topbar-sep">›</span>
      <span class="topbar-current">Today's Reflection</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">

      {{-- Hero --}}
      <div class="hero-card hero-card-dark">
        <a href="{{ url('/dashboard') }}" class="back-link">← Back to Home</a>
        <div class="hero-eyebrow hero-eyebrow-amber">{{ now()->format('F j, Y (D)') }}</div>
        <div class="hero-title">Today's Reflection ✍️</div>
        <div class="hero-sub">Record today's events, emotions, and insights to discover your growth patterns.</div>
      </div>

      <form method="POST" action="{{ route('reflection.store') }}">
        @csrf
        <div class="row g-4">

          {{-- ── Left ── --}}
          <div class="col-8">

            {{-- Mood Score --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div class="card-title-custom">Today's Mood Score</div>
              @error('mood')<div class="error-msg">{{ $message }}</div>@enderror
              <div class="d-flex gap-2 mb-3">
                @foreach([
                  ['😞','1','label-rose'],
                  ['😐','2','label-amber'],
                  ['🙂','3','label-amber'],
                  ['😊','4','label-amber'],
                  ['🤩','5','label-sage'],
                ] as [$emoji,$val,$colorClass])
                <div class="mood-opt" :class="{ active: mood == {{ $val }} }" @click="mood = {{ $val }}">
                  <div class="mood-opt-emoji">{{ $emoji }}</div>
                  <div class="mood-opt-val {{ $colorClass }}">{{ $val }}</div>
                </div>
                @endforeach
              </div>
              <div class="mood-score-text"
                   :style="{ color: moodColors[mood] }"
                   x-text="mood ? moodLabels[mood] + ' is selected' : 'Please select your mood score'">
              </div>
              <input type="hidden" name="mood" :value="mood">
            </div>

            {{-- Journal --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div class="card-title-custom">Journal</div>
              @error('journal')<div class="error-msg">{{ $message }}</div>@enderror
              <textarea name="journal" class="form-textarea-custom journal-textarea"
                        placeholder="Freely write about what happened, how you felt, and what you noticed today…"
                        maxlength="500" x-model="journal">{{ old('journal') }}</textarea>
              <div class="d-flex justify-content-end mt-1">
                <span class="char-counter"
                      :class="charCount >= 480 ? 'char-counter-warn' : 'char-counter-normal'"
                      x-text="charCount + ' / 500 characters'"></span>
              </div>
            </div>

            {{-- Tags --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div class="card-title-custom">
                Today's Tags <span class="tag-hint">(multiple allowed)</span>
              </div>
              <div class="d-flex flex-wrap gap-2">
                @foreach([
                  ['💼 Work',        'work',        'label-ink',    'var(--cream)'],
                  ['🏃 Exercise',    'exercise',    'label-sage',   'var(--sage-pale)'],
                  ['📚 Study',       'study',       'label-blue',   'var(--blue-pale)'],
                  ['❤️ Family',      'family',      'label-rose',   'var(--rose-pale)'],
                  ['🌱 Growth',      'growth',      'label-amber',  'var(--amber-pale)'],
                  ['🧘 Mental',      'mental',      'label-purple', 'var(--purple-pale)'],
                  ['🎯 Achievement', 'achievement', 'label-teal',   'var(--teal-pale)'],
                ] as [$label,$val,$colorClass,$bg])
                <span class="tag-chip {{ $colorClass }}"
                      :class="{ active: tags.includes('{{ $val }}') }"
                      :style="tags.includes('{{ $val }}') ? { background:'{{ $bg }}' } : {}"
                      @click="toggleTag('{{ $val }}')">{{ $label }}</span>
                @endforeach
              </div>
              <input type="hidden" name="tags" :value="JSON.stringify(tags)">
            </div>

            {{-- 3-Part Reflection --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div class="card-title-custom">3-Part Reflection</div>
              <div class="mb-4">
                <label class="form-label-custom label-sage">🌟 What I'm Grateful For Today</label>
                <textarea name="grateful" class="form-textarea-custom form-textarea-sm"
                          placeholder="Even small things count. Write something you feel grateful for…">{{ old('grateful') }}</textarea>
              </div>
              <div class="mb-4">
                <label class="form-label-custom label-amber">💡 What I Want to Improve Tomorrow</label>
                <textarea name="improve" class="form-textarea-custom form-textarea-sm"
                          placeholder="What could you have done better? What stood out?">{{ old('improve') }}</textarea>
              </div>
              <div>
                <label class="form-label-custom label-blue">📌 Tomorrow's To-Do (up to 3)</label>
                <div class="d-flex flex-column gap-2">
                  @foreach([1,2,3] as $i)
                  <div class="todo-row">
                    <div class="todo-checkbox"></div>
                    <input type="text" name="todo[]" class="form-input-custom"
                           placeholder="Todo {{ $i }}" value="{{ old('todo.'.($i-1)) }}">
                  </div>
                  @endforeach
                </div>
              </div>
            </div>

            {{-- Buttons --}}
            <div class="d-flex gap-3 align-items-center">
              <button type="submit" class="btn-amber">💾 Save Reflection</button>
              <a href="{{ url('/dashboard') }}" class="btn-outline">Cancel</a>
            </div>

          </div>

          {{-- ── Right Sidebar ── --}}
          <div class="col-4">

            {{-- This Week's Mood Trend --}}
            <div class="dark-widget">
              <div class="dark-widget-title">This Week's Mood Trend</div>
              <div class="mood-bar-wrap">
                @php
                  $moodBarColors = [1=>'#C4716A',2=>'rgba(200,134,58,.55)',3=>'rgba(200,134,58,.8)',4=>'var(--sage)',5=>'#5DCAA5'];
                  $moodHeights   = [1=>20,2=>40,3=>60,4=>80,5=>100];
                @endphp
                @foreach($weekMoods as $day)
                <div class="mood-bar-col">
                  @if($day['mood'])
                    <div style="background:{{ $moodBarColors[$day['mood']] }};height:{{ $moodHeights[$day['mood']] }}%;border-radius:3px 3px 0 0;width:100%;"></div>
                  @elseif($day['today'])
                    <div class="mood-bar-fill-today"></div>
                  @else
                    <div class="mood-bar-fill-empty"></div>
                  @endif
                  <div class="mood-bar-label {{ $day['today'] ? 'mood-bar-label-today' : 'mood-bar-label-normal' }}">
                    {{ $day['label'] }}
                  </div>
                </div>
                @endforeach
              </div>
              <div class="mood-bar-axis">
                <span>😞 Low</span><span>🙂 Mid</span><span>🤩 High</span>
              </div>
            </div>

            {{-- Mood Score Distribution --}}
            <div class="dark-widget">
              <div class="dark-widget-title">Mood Score Distribution</div>
              @if($totalReflections > 0)
                @php
                  $moodEmojisChart = [1=>'😞',2=>'😐',3=>'🙂',4=>'😊',5=>'🤩'];
                  $moodLabelChart  = [1=>'Very Low',2=>'Low',3=>'Okay',4=>'Good',5=>'Excellent'];
                  $moodBarCol      = [1=>'#C4716A',2=>'#BA7517',3=>'#C8863A',4=>'#7A9E7E',5=>'#5DCAA5'];
                  $maxCount        = max($moodCounts + [0]);
                @endphp
                @foreach([5,4,3,2,1] as $score)
                @php
                  $count      = $moodCounts[$score] ?? 0;
                  $pct        = $maxCount > 0 ? round($count / $maxCount * 100) : 0;
                  $pctOfTotal = $totalReflections > 0 ? round($count / $totalReflections * 100) : 0;
                @endphp
                <div class="mood-dist-row">
                  <span class="mood-dist-emoji">{{ $moodEmojisChart[$score] }}</span>
                  <div class="mood-dist-track">
                    <div class="mood-dist-fill" style="background:{{ $moodBarCol[$score] }};width:{{ $pct }}%;"></div>
                  </div>
                  <span class="mood-dist-count">{{ $count }}回 ({{ $pctOfTotal }}%)</span>
                </div>
                @endforeach
                @php $mostCommonMood = array_search(max($moodCounts), $moodCounts); @endphp
                <div class="mood-dist-footer">
                  Most frequent:
                  <span style="color:{{ $moodBarCol[$mostCommonMood] }};font-weight:700;">
                    {{ $moodEmojisChart[$mostCommonMood] }} {{ $moodLabelChart[$mostCommonMood] }} (Score {{ $mostCommonMood }})
                  </span>
                </div>
              @else
                <div class="mood-dist-empty">
                  No data yet.<br>Start logging to see your mood trends!
                </div>
              @endif
            </div>

            {{-- Logging Tips --}}
            <div class="card border-0 shadow-sm rounded-4 p-4">
              <div class="card-title-custom">Logging Tips 💡</div>
              <div class="d-flex flex-column gap-3">
                <div class="tip-item"><span class="label-amber">💡</span><span>Writing specific episodes makes memories much clearer when you look back.</span></div>
                <div class="tip-item"><span class="label-sage">🌱</span><span>Filling in the gratitude section every day builds a habit of positive thinking.</span></div>
                <div class="tip-item"><span class="label-blue">📌</span><span>Keeping tomorrow's to-do to 3 or fewer dramatically increases completion rates.</span></div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
