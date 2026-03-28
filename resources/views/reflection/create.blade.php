<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daily Reflection — Memo Diary</title>
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
      --teal:#4A9B8E; --teal-pale:#DCF0EE;
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
    .hero-card { background:linear-gradient(140deg,#2A2420 0%,#3D3228 60%,#4A3C2E 100%); border-radius:20px; padding:36px; position:relative; overflow:hidden; color:white; margin-bottom:28px; }
    .hero-card::before { content:""; position:absolute; width:300px; height:300px; background:radial-gradient(circle,rgba(200,134,58,.2) 0%,transparent 70%); top:-80px; right:-60px; border-radius:50%; }
    .hero-eyebrow { font-size:11px; font-weight:700; letter-spacing:.15em; text-transform:uppercase; color:var(--amber-light); margin-bottom:8px; position:relative; }
    .hero-title { font-family:'DM Serif Display',serif; font-size:32px; color:white; margin-bottom:6px; position:relative; }
    .hero-sub { font-size:14px; color:rgba(255,255,255,.45); position:relative; }
    .back-link { display:inline-flex; align-items:center; gap:6px; font-size:13px; color:rgba(255,255,255,.5); text-decoration:none; margin-bottom:16px; transition:.18s; position:relative; }
    .back-link:hover { color:white; }
    .mood-opt { flex:1; padding:14px 8px; border-radius:12px; border:2px solid rgba(28,26,23,.1); text-align:center; background:white; cursor:pointer; transition:all .15s; }
    .mood-opt:hover { border-color:var(--amber); transform:translateY(-2px); }
    .mood-opt.active { border-color:var(--amber); background:var(--amber-pale); }
    .tag-chip { display:inline-flex; align-items:center; padding:6px 14px; border-radius:22px; font-size:12px; font-weight:600; border:1.5px solid rgba(28,26,23,.1); background:white; cursor:pointer; transition:all .15s; }
    .card-title-custom { font-size:10.5px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:14px; }
    .form-label-custom { font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; display:block; margin-bottom:6px; }
    .form-textarea-custom { width:100%; padding:12px 14px; border-radius:10px; border:1.5px solid rgba(28,26,23,.1); background:white; font-family:'DM Sans',sans-serif; font-size:14px; color:var(--ink); outline:none; transition:border-color .2s; resize:vertical; }
    .form-textarea-custom:focus { border-color:var(--amber); }
    .form-input-custom { width:100%; padding:10px 14px; border-radius:10px; border:1.5px solid rgba(28,26,23,.1); background:white; font-family:'DM Sans',sans-serif; font-size:14px; color:var(--ink); outline:none; transition:border-color .2s; }
    .form-input-custom:focus { border-color:var(--blue); }
    .btn-save { background:var(--amber); color:white; border:none; border-radius:12px; padding:14px 36px; font-size:14px; font-weight:700; cursor:pointer; transition:.18s; font-family:'DM Sans',sans-serif; }
    .btn-save:hover { background:#B8762A; transform:translateY(-1px); }
    .btn-cancel { background:white; color:var(--ink-muted); border:1.5px solid rgba(28,26,23,.12); border-radius:12px; padding:14px 24px; font-size:14px; font-weight:600; cursor:pointer; font-family:'DM Sans',sans-serif; text-decoration:none; display:inline-block; }
  </style>
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

  <div class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon"><img src="{{ asset('images/logo.jpg') }}" style="width:40px;height:40px;object-fit:cover;border-radius:12px;display:block;"></div>
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
      <span class="text-muted" style="font-size:12px;">Daily Reflection</span>
      <span class="text-muted mx-2" style="opacity:.4;">›</span>
      <span style="font-size:15px;font-weight:600;color:var(--ink);">Today's Reflection</span>
      <div class="ms-auto"><a href="{{ route('settings') }}" class="topbar-btn">⚙️</a></div>
    </div>

    <div class="p-4">
      <div class="hero-card">
        <a href="{{ url('/dashboard') }}" class="back-link">← Back to Home</a>
        <div class="hero-eyebrow">{{ now()->format('F j, Y (D)') }}</div>
        <div class="hero-title">Today's Reflection ✍️</div>
        <div class="hero-sub">Record today's events, emotions, and insights to discover your growth patterns.</div>
      </div>

      <form method="POST" action="{{ route('reflection.store') }}">
        @csrf
        <div class="row g-4">
          <div class="col-8">

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div class="card-title-custom">Today's Mood Score</div>
              @error('mood')<div style="font-size:12px;color:var(--rose);margin-bottom:8px;">{{ $message }}</div>@enderror
              <div class="d-flex gap-2 mb-3">
                @foreach([['😞','1','var(--rose)'],['😐','2','var(--amber)'],['🙂','3','var(--amber)'],['😊','4','var(--amber)'],['🤩','5','var(--sage)']] as [$emoji,$val,$color])
                <div class="mood-opt" :class="{ active: mood == {{ $val }} }" @click="mood = {{ $val }}">
                  <div style="font-size:26px;">{{ $emoji }}</div>
                  <div style="font-size:11px;font-weight:600;color:{{ $color }};margin-top:4px;">{{ $val }}</div>
                </div>
                @endforeach
              </div>
              <div style="font-size:12px;text-align:center;transition:.2s;" :style="{ color: moodColors[mood] }" x-text="mood ? moodLabels[mood] + ' is selected' : 'Please select your mood score'"></div>
              <input type="hidden" name="mood" :value="mood">
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div class="card-title-custom">Journal</div>
              @error('journal')<div style="font-size:12px;color:var(--rose);margin-bottom:8px;">{{ $message }}</div>@enderror
              <textarea name="journal" class="form-textarea-custom" style="min-height:140px" placeholder="Freely write about what happened, how you felt, and what you noticed today…" maxlength="500" x-model="journal">{{ old('journal') }}</textarea>
              <div class="d-flex justify-content-end mt-1">
                <span style="font-size:11px;" :style="{ color: charCount >= 480 ? 'var(--rose)' : 'var(--ink-muted)' }" x-text="charCount + ' / 500 characters'"></span>
              </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div class="card-title-custom">Today's Tags <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#B4B2A9;">(multiple allowed)</span></div>
              <div class="d-flex flex-wrap gap-2">
                @foreach([['💼 Work','work','var(--ink)','var(--cream)'],['🏃 Exercise','exercise','var(--sage)','var(--sage-pale)'],['📚 Study','study','var(--blue)','var(--blue-pale)'],['❤️ Family','family','var(--rose)','var(--rose-pale)'],['🌱 Growth','growth','var(--amber)','var(--amber-pale)'],['🧘 Mental','mental','var(--purple)','var(--purple-pale)'],['🎯 Achievement','achievement','var(--teal)','var(--teal-pale)']] as [$label,$val,$color,$bg])
                <span class="tag-chip" :class="{ active: tags.includes('{{ $val }}') }" :style="tags.includes('{{ $val }}') ? { background:'{{ $bg }}', borderColor:'{{ $color }}', color:'{{ $color }}' } : { color:'{{ $color }}' }" @click="toggleTag('{{ $val }}')">{{ $label }}</span>
                @endforeach
              </div>
              <input type="hidden" name="tags" :value="JSON.stringify(tags)">
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
              <div class="card-title-custom">3-Part Reflection</div>
              <div class="mb-4">
                <label class="form-label-custom" style="color:var(--sage);">🌟 What I'm Grateful For Today</label>
                <textarea name="grateful" class="form-textarea-custom" style="min-height:80px" placeholder="Even small things count. Write something you feel grateful for…">{{ old('grateful') }}</textarea>
              </div>
              <div class="mb-4">
                <label class="form-label-custom" style="color:var(--amber);">💡 What I Want to Improve Tomorrow</label>
                <textarea name="improve" class="form-textarea-custom" style="min-height:80px" placeholder="What could you have done better? What stood out?">{{ old('improve') }}</textarea>
              </div>
              <div>
                <label class="form-label-custom" style="color:var(--blue);">📌 Tomorrow's To-Do (up to 3)</label>
                <div class="d-flex flex-column gap-2">
                  @foreach([1,2,3] as $i)
                  <div class="d-flex align-items-center gap-2">
                    <div style="width:22px;height:22px;border-radius:6px;border:2px solid rgba(28,26,23,.15);flex-shrink:0;"></div>
                    <input type="text" name="todo[]" class="form-input-custom" placeholder="Todo {{ $i }}" value="{{ old('todo.'.($i-1)) }}">
                  </div>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="d-flex gap-3 align-items-center">
              <button type="submit" class="btn-save">💾 Save Reflection</button>
              <a href="{{ url('/dashboard') }}" class="btn-cancel">Cancel</a>
            </div>
          </div>

          <div class="col-4">
            <div style="background:linear-gradient(135deg,#2A2420,#3D3228);border-radius:18px;padding:22px;margin-bottom:16px;">
              <div style="font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--amber-light);margin-bottom:14px;">This Week's Mood Trend</div>
              <div class="d-flex align-items-end gap-2" style="height:72px;">
                @php $moodBarColors=[1=>'#C4716A',2=>'rgba(200,134,58,.55)',3=>'rgba(200,134,58,.8)',4=>'var(--sage)',5=>'#5DCAA5']; $moodHeights=[1=>20,2=>40,3=>60,4=>80,5=>100]; @endphp
                @foreach($weekMoods as $day)
                <div class="flex-fill d-flex flex-column align-items-center gap-1">
                  @if($day['mood'])
                    <div style="background:{{ $moodBarColors[$day['mood']] }};height:{{ $moodHeights[$day['mood']] }}%;border-radius:3px 3px 0 0;width:100%;"></div>
                  @else
                    <div style="background:{{ $day['today'] ? 'rgba(255,255,255,.15)' : 'rgba(255,255,255,.08)' }};height:28%;border-radius:3px 3px 0 0;width:100%;{{ $day['today'] ? 'border:1px dashed rgba(255,255,255,.3);' : '' }}"></div>
                  @endif
                  <div style="font-size:9px;color:{{ $day['today'] ? 'var(--amber-light)' : 'rgba(255,255,255,.4)' }};font-weight:{{ $day['today'] ? '700' : '400' }};">{{ $day['label'] }}</div>
                </div>
                @endforeach
              </div>
              <div class="d-flex justify-content-between mt-2" style="font-size:9px;color:rgba(255,255,255,.3);">
                <span>😞 Low</span><span>🙂 Mid</span><span>🤩 High</span>
              </div>
            </div>

            <div style="background:linear-gradient(135deg,#2A2420,#3D3228);border-radius:18px;padding:22px;margin-bottom:16px;">
              <div style="font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--amber-light);margin-bottom:14px;">Mood Score Distribution</div>
              @if($totalReflections > 0)
                @php $moodEmojisChart=[1=>'😞',2=>'😐',3=>'🙂',4=>'😊',5=>'🤩']; $moodLabelChart=[1=>'Very Low',2=>'Low',3=>'Okay',4=>'Good',5=>'Excellent']; $moodBarCol=[1=>'#C4716A',2=>'#BA7517',3=>'#C8863A',4=>'#7A9E7E',5=>'#5DCAA5']; $maxCount=max($moodCounts+[0]); @endphp
                @foreach([5,4,3,2,1] as $score)
                @php $count=$moodCounts[$score]??0; $pct=$maxCount>0?round($count/$maxCount*100):0; $pctOfTotal=$totalReflections>0?round($count/$totalReflections*100):0; @endphp
                <div class="mb-2">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <span style="font-size:14px;width:20px;text-align:center;">{{ $moodEmojisChart[$score] }}</span>
                    <div style="flex:1;height:10px;background:rgba(255,255,255,.1);border-radius:5px;overflow:hidden;">
                      <div style="height:10px;background:{{ $moodBarCol[$score] }};border-radius:5px;width:{{ $pct }}%;"></div>
                    </div>
                    <span style="font-size:11px;color:rgba(255,255,255,.5);min-width:48px;text-align:right;">{{ $count }}回 ({{ $pctOfTotal }}%)</span>
                  </div>
                </div>
                @endforeach
                @php $mostCommonMood=array_search(max($moodCounts),$moodCounts); @endphp
                <div style="margin-top:12px;padding-top:12px;border-top:1px solid rgba(255,255,255,.08);font-size:12px;color:rgba(255,255,255,.5);">
                  Most frequent: <span style="color:{{ $moodBarCol[$mostCommonMood] }};font-weight:700;">{{ $moodEmojisChart[$mostCommonMood] }} {{ $moodLabelChart[$mostCommonMood] }} (Score {{ $mostCommonMood }})</span>
                </div>
              @else
                <div style="text-align:center;color:rgba(255,255,255,.3);font-size:13px;padding:16px 0;">No data yet.<br>Start logging to see your mood trends!</div>
              @endif
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4">
              <div class="card-title-custom">Logging Tips 💡</div>
              <div class="d-flex flex-column gap-3" style="font-size:13px;color:var(--ink-muted);line-height:1.65;">
                <div class="d-flex gap-2"><span style="color:var(--amber);">💡</span><span>Writing specific episodes makes memories much clearer when you look back.</span></div>
                <div class="d-flex gap-2"><span style="color:var(--sage);">🌱</span><span>Filling in the gratitude section every day builds a habit of positive thinking.</span></div>
                <div class="d-flex gap-2"><span style="color:var(--blue);">📌</span><span>Keeping tomorrow's to-do to 3 or fewer dramatically increases completion rates.</span></div>
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
