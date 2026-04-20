<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Reflection — Memo Diary</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/memo-diary.css') }}" rel="stylesheet">
  <script src="{{ asset('js/alpine.min.js') }}" defer></script>
</head>
<body x-data="{
  mood: {{ $reflection->mood }},
  journal: '{{ addslashes($reflection->journal) }}',
  tags: {{ json_encode($reflection->tags ?? []) }},
  get charCount() { return this.journal.length; },
  toggleTag(tag) { if(this.tags.includes(tag)){this.tags=this.tags.filter(t=>t!==tag);}else{this.tags.push(tag);} },
  moodLabels: ['','😞 Very Low','😐 Low','🙂 Okay','😊 Very Good','🤩 Excellent'],
  moodColors: ['','#C4716A','#BA7517','#BA7517','#C8863A','#7A9E7E'],
}">

  {{-- ════ SIDEBAR ════ --}}
  <div class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon"><img src="{{ asset('images/logo.jpg') }}" class="logo-img"></div>
      <div><div class="logo-name">Memo Diary</div><div class="logo-tagline">SELF-GROWTH LOG</div></div>
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
      <span class="topbar-current">Edit Reflection</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">

      {{-- Hero --}}
      <div class="hero-card hero-card-dark">
        <a href="{{ url('/dashboard') }}" class="back-link">← Back to Home</a>
        <div class="hero-eyebrow hero-eyebrow-amber">{{ $reflection->created_at->format('F j, Y (D)') }}</div>
        <div class="hero-title">Edit Reflection ✏️</div>
        <div class="hero-sub">Update your reflection entry below.</div>
      </div>

      <form method="POST" action="{{ route('reflection.update', $reflection) }}">
        @csrf @method('PUT')
        <div class="row g-4">

          {{-- ── Left ── --}}
          <div class="col-8">

            {{-- Mood Score --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div class="card-title-custom">Mood Score</div>
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
              <textarea name="journal" class="form-textarea-custom journal-textarea"
                        placeholder="Freely write about what happened…"
                        maxlength="500" x-model="journal">{{ old('journal', $reflection->journal) }}</textarea>
              <div class="d-flex justify-content-end mt-1">
                <span class="char-counter"
                      :class="charCount >= 480 ? 'char-counter-warn' : 'char-counter-normal'"
                      x-text="charCount + ' / 500 characters'"></span>
              </div>
            </div>

            {{-- Tags --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div class="card-title-custom">Tags</div>
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
                          placeholder="Even small things count…">{{ old('grateful', $reflection->grateful) }}</textarea>
              </div>
              <div class="mb-4">
                <label class="form-label-custom label-amber">💡 What I Want to Improve Tomorrow</label>
                <textarea name="improve" class="form-textarea-custom form-textarea-sm"
                          placeholder="What could you have done better?">{{ old('improve', $reflection->improve) }}</textarea>
              </div>
              <div>
                <label class="form-label-custom label-blue">📌 Tomorrow's To-Do (up to 3)</label>
                <div class="d-flex flex-column gap-2">
                  @foreach([0,1,2] as $i)
                  <div class="todo-row">
                    <div class="todo-checkbox"></div>
                    <input type="text" name="todo[]" class="form-input-custom"
                           placeholder="Todo {{ $i+1 }}"
                           value="{{ old('todo.'.$i, $reflection->todos[$i] ?? '') }}">
                  </div>
                  @endforeach
                </div>
              </div>
            </div>

            {{-- Buttons --}}
            <div class="d-flex gap-3 align-items-center">
              <button type="submit" class="btn-amber">✏️ Update Reflection</button>
              <a href="{{ url('/dashboard') }}" class="btn-outline">Cancel</a>
            </div>

          </div>

          {{-- ── Right ── --}}
          <div class="col-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
              <div class="card-title-custom">Original Entry</div>
              <div class="edit-original-meta">Created: {{ $reflection->created_at->format('F j, Y') }}</div>
              <div class="edit-original-journal">{{ $reflection->journal }}</div>
              @if($reflection->grateful)
                <div class="edit-original-grateful-label">🌟 Grateful</div>
                <div class="edit-original-grateful-text">{{ $reflection->grateful }}</div>
              @endif
              @if(!empty($reflection->todos))
                <div class="edit-original-todo-label">📌 To-Do</div>
                @foreach($reflection->todos as $todo)
                  <div class="edit-original-todo-item">• {{ $todo }}</div>
                @endforeach
              @endif
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>

  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
