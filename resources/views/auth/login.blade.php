<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Memo Diary — Login / Sign Up</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="{{ asset('js/alpine.min.js') }}" defer></script>
  <style>
    :root {
      --amber:#C8863A; --amber-light:#E8B86D; --amber-pale:#F5E4C8;
      --ink:#1C1A17; --ink-muted:#8C8680; --cream:#F5F0E8; --warm-white:#FDFAF4;
    }
    body { font-family:'DM Sans',sans-serif; background:#F0EAE0; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px; }
    .auth-wrap { width:100%; max-width:900px; border-radius:24px; overflow:hidden; box-shadow:0 12px 40px rgba(28,26,23,.15); display:grid; grid-template-columns:1fr 1fr; }
    .auth-left { background:linear-gradient(155deg,#2A2420,#3D3228); padding:52px 44px; display:flex; flex-direction:column; justify-content:space-between; position:relative; overflow:hidden; }
    .auth-left::before { content:""; position:absolute; width:350px; height:350px; background:radial-gradient(circle,rgba(200,134,58,.2) 0%,transparent 70%); top:-100px; right:-80px; pointer-events:none; }
    .auth-left::after { content:""; position:absolute; width:200px; height:200px; background:radial-gradient(circle,rgba(200,134,58,.1) 0%,transparent 70%); bottom:40px; left:30px; pointer-events:none; }
    .auth-logo-icon { width:60px; height:60px; border-radius:18px; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; font-size:26px; margin-bottom:18px; }
    .auth-app-name { font-family:'DM Serif Display',serif; font-size:34px; color:white; margin-bottom:6px; }
    .auth-tagline { font-size:13px; color:rgba(255,255,255,.4); line-height:1.6; }
    .auth-feat-icon { width:36px; height:36px; border-radius:10px; background:rgba(200,134,58,.2); display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
    .feat-title { font-size:13px; font-weight:600; color:white; margin-bottom:2px; }
    .feat-sub { font-size:12px; color:rgba(255,255,255,.5); line-height:1.5; }
    .auth-right { background:var(--warm-white); padding:48px 44px; display:flex; flex-direction:column; justify-content:center; }
    .auth-tabs { display:flex; background:var(--cream); border-radius:12px; padding:4px; margin-bottom:28px; }
    .auth-tab-btn { flex:1; padding:10px; border-radius:9px; border:none; font-family:'DM Sans',sans-serif; font-size:14px; font-weight:500; cursor:pointer; transition:.2s; background:transparent; color:var(--ink-muted); }
    .auth-tab-btn.active { background:white; color:var(--ink); font-weight:600; box-shadow:0 2px 10px rgba(28,26,23,.1); }
    .form-label-custom { font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--ink-muted); margin-bottom:6px; display:block; }
    .form-input-custom { width:100%; padding:11px 14px; border-radius:10px; border:1.5px solid rgba(28,26,23,.1); background:white; font-family:'DM Sans',sans-serif; font-size:14px; color:var(--ink); outline:none; transition:border-color .2s; }
    .form-input-custom:focus { border-color:var(--amber); }
    .btn-submit { width:100%; padding:13px; border-radius:12px; border:none; background:var(--amber); color:white; font-family:'DM Sans',sans-serif; font-size:14px; font-weight:600; cursor:pointer; transition:.18s; margin-top:4px; }
    .btn-submit:hover { background:#B8762A; transform:translateY(-1px); }
    .forgot-link { font-size:12px; color:var(--amber); cursor:pointer; text-decoration:none; }
    .forgot-link:hover { text-decoration:underline; }
    .terms-text { font-size:11px; color:var(--ink-muted); text-align:center; margin-top:12px; line-height:1.6; }
    .terms-text a { color:var(--amber); text-decoration:none; }
    .alert-custom { padding:10px 14px; border-radius:10px; font-size:13px; margin-bottom:16px; }
    .alert-error { background:#FEF2F2; border:1px solid #FECACA; color:#B91C1C; }
  </style>
</head>
<body>

<div class="auth-wrap" x-data="{
  tab: '{{ $errors->has('name') || old('name') ? 'register' : 'login' }}',
  password: '',
  get strength() {
    let s = 0;
    if (this.password.length >= 8) s++;
    if (/[A-Z]/.test(this.password)) s++;
    if (/[0-9]/.test(this.password)) s++;
    if (/[^A-Za-z0-9]/.test(this.password)) s++;
    return s;
  },
  get strengthLabel() { return ['','Weak','Fair','Good','Strong'][this.strength]; },
  get strengthColor() { return ['','#E24B4A','#BA7517','#7A9E7E','#1D9E75'][this.strength]; }
}">

  {{-- 左パネル --}}
  <div class="auth-left">
    <div style="position:relative">
      <div class="auth-logo-icon">📔</div>
      <div class="auth-app-name">Memo Diary</div>
      <div class="auth-tagline">Turn thoughts & actions into assets<br>Self-Growth Log App</div>
    </div>
    <div style="position:relative">
      <div class="d-flex gap-3 align-items-start mb-4">
        <div class="auth-feat-icon">📊</div>
        <div><div class="feat-title">Visualize Growth Scores</div><div class="feat-sub">AI analyzes and quantifies your growth every week</div></div>
      </div>
      <div class="d-flex gap-3 align-items-start mb-4">
        <div class="auth-feat-icon">🎯</div>
        <div><div class="feat-title">Manage Goals & Habits</div><div class="feat-sub">Track weekly & monthly goal achievement rates</div></div>
      </div>
      <div class="d-flex gap-3 align-items-start">
        <div class="auth-feat-icon">✨</div>
        <div><div class="feat-title">AI Personal Coach</div><div class="feat-sub">Personalized suggestions based on behavior patterns</div></div>
      </div>
    </div>
    <div style="font-size:11px;color:rgba(255,255,255,.2)">© 2025 Memo Diary</div>
  </div>

  {{-- 右パネル --}}
  <div class="auth-right">
    <div class="auth-tabs">
      <button class="auth-tab-btn" :class="{ active: tab === 'login' }" @click="tab = 'login'">Log In</button>
      <button class="auth-tab-btn" :class="{ active: tab === 'register' }" @click="tab = 'register'">Sign Up</button>
    </div>

    {{-- ログインフォーム --}}
    <div x-show="tab === 'login'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
      <div style="font-family:'DM Serif Display',serif;font-size:28px;color:var(--ink);margin-bottom:4px;">Welcome Back</div>
      <p style="font-size:13px;color:var(--ink-muted);margin-bottom:24px;">Please log in to your account</p>
      @if ($errors->any() && !$errors->has('name'))
        <div class="alert-custom alert-error">{{ $errors->first() }}</div>
      @endif
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label-custom">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" required autofocus class="form-input-custom" placeholder="your@email.com">
        </div>
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label-custom" style="margin-bottom:0">Password</label>
            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
          </div>
          <input type="password" name="password" required class="form-input-custom" placeholder="••••••••">
        </div>
        <div class="d-flex align-items-center gap-2 mb-4">
          <input type="checkbox" name="remember" id="remember" class="form-check-input" style="width:16px;height:16px;accent-color:var(--amber)">
          <label for="remember" style="font-size:13px;color:var(--ink-muted);cursor:pointer;">Remember me</label>
        </div>
        <button type="submit" class="btn-submit">Log In</button>
      </form>
      <p style="font-size:13px;color:var(--ink-muted);text-align:center;margin-top:20px;">
        Don't have an account? <a href="#" @click.prevent="tab = 'register'" style="color:var(--amber);font-weight:600;text-decoration:none;">Sign Up</a>
      </p>
    </div>

    {{-- サインアップフォーム --}}
    <div x-show="tab === 'register'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
      <div style="font-family:'DM Serif Display',serif;font-size:28px;color:var(--ink);margin-bottom:4px;">Create Account</div>
      <p style="font-size:13px;color:var(--ink-muted);margin-bottom:24px;">Start your growth journey for free</p>
      @if ($errors->has('name') || ($errors->has('email') && old('name')))
        <div class="alert-custom alert-error">{{ $errors->first() }}</div>
      @endif
      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label-custom">Name</label>
          <input type="text" name="name" value="{{ old('name') }}" required class="form-input-custom" placeholder="Your name">
        </div>
        <div class="mb-3">
          <label class="form-label-custom">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" required class="form-input-custom" placeholder="your@email.com">
        </div>
        <div class="mb-1">
          <label class="form-label-custom">Password</label>
          <input type="password" name="password" required class="form-input-custom" placeholder="8 characters or more" x-model="password">
        </div>
        <div class="mb-3">
          <div style="background:#F1EFE8;border-radius:2px;height:4px;margin-bottom:4px;">
            <div style="height:4px;border-radius:2px;transition:width .3s,background .3s;" :style="{ width: (strength * 25) + '%', background: strengthColor }"></div>
          </div>
          <div style="font-size:11px;" :style="{ color: strengthColor }" x-text="password.length ? strengthLabel : ''"></div>
        </div>
        <div class="mb-4">
          <label class="form-label-custom">Confirm Password</label>
          <input type="password" name="password_confirmation" required class="form-input-custom" placeholder="Re-enter password">
        </div>
        <button type="submit" class="btn-submit">Create Account</button>
        <p class="terms-text">By signing up, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></p>
      </form>
      <p style="font-size:13px;color:var(--ink-muted);text-align:center;margin-top:16px;">
        Already have an account? <a href="#" @click.prevent="tab = 'login'" style="color:var(--amber);font-weight:600;text-decoration:none;">Log In</a>
      </p>
    </div>

  </div>
</div>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
