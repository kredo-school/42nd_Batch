<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Memo Diary')</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/memo-diary.css') }}" rel="stylesheet">
  @yield('css')
</head>
<body @yield('body-attr')>

  {{-- ════ SIDEBAR ════ --}}
  <div class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon">
        <img src="{{ asset('images/logo.jpg') }}" class="logo-img">
      </div>
      <div>
        <div class="logo-name">Memo Diary</div>
        <div class="logo-tagline">SELF-GROWTH LOG</div>
      </div>
    </div>
    <div class="nav-section-label">Main</div>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}"><span>🏠</span> Home</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->is('reflection*') ? 'active' : '' }}" href="{{ route('reflection.create') }}"><span>✍️</span> Daily Reflection</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->is('activity*') ? 'active' : '' }}" href="{{ route('activity.create') }}"><span>🏃</span> Activity Log</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->is('goals*') ? 'active' : '' }}" href="{{ route('goal.index') }}"><span>🎯</span> Goal Tracking</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->is('analytics*') ? 'active' : '' }}" href="{{ route('analytics') }}"><span>📊</span> Analytics</a></li>
    </ul>
    <div class="nav-section-label">Personal</div>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}" href="{{ route('profile') }}"><span>👤</span> Profile</a></li>
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
      @yield('breadcrumb')
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

      @yield('content')

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @yield('scripts')
</body>
</html>
