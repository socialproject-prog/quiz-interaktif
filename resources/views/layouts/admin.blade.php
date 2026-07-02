<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand brand-admin">
            <span class="brand-mark">Q</span>
            <span>Quiz Interaktif</span>
        </a>

        <nav class="sidebar-nav">
            <p class="nav-label">MENU UTAMA</p>
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <span>⌂</span> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('admin.quizzes.*') || request()->routeIs('admin.questions.*') ? 'active' : '' }}" href="{{ route('admin.quizzes.index') }}">
                <span>▣</span> Kelola Kuis
            </a>
            <p class="nav-label">AKSES CEPAT</p>
            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                <span>↗</span> Halaman Peserta
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <strong>{{ auth()->user()->name }}</strong>
                <small>{{ auth()->user()->email }}</small>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button class="icon-button" title="Keluar">⇥</button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        <header class="topbar">
            <button class="icon-button mobile-menu" type="button" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
            <div>
                <strong>@yield('page-title', 'Dashboard')</strong>
                <span class="topbar-subtitle">@yield('page-subtitle')</span>
            </div>
            <div class="topbar-actions">
                <a class="btn btn-light btn-sm" href="{{ route('home') }}" target="_blank">Lihat Situs</a>
            </div>
        </header>

        <section class="content-wrap">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Periksa kembali data berikut:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </section>
    </main>
</div>
@stack('scripts')
</body>
</html>
