<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MVC Exercises') – Laravel MVC</title>
    <meta name="description" content="Colección de 11 aplicaciones web construidas con Laravel y arquitectura MVC.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-primary:    #0f0f1a;
            --bg-secondary:  #161628;
            --bg-card:       #1e1e35;
            --bg-hover:      #252545;
            --border:        rgba(255,255,255,0.08);
            --text-primary:  #f0f0ff;
            --text-secondary:#a0a0c0;
            --text-muted:    #6060a0;
            --accent:        #6366f1;
            --accent-light:  #818cf8;
            --success:       #10b981;
            --danger:        #ef4444;
            --warning:       #f59e0b;
            --radius:        14px;
            --radius-sm:     8px;
            --shadow:        0 8px 32px rgba(0,0,0,0.4);
            --sidebar-w:     260px;
            --transition:    all 0.25s cubic-bezier(0.4,0,0.2,1);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            transition: var(--transition);
        }
        .sidebar-brand {
            padding: 24px 20px 16px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand a {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-brand .logo {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), #a855f7);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .sidebar-brand .brand-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }
        .sidebar-brand .brand-sub {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 400;
        }
        .sidebar-nav {
            flex: 1;
            padding: 12px 10px;
            overflow-y: auto;
        }
        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            padding: 10px 10px 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            transition: var(--transition);
            margin-bottom: 2px;
        }
        .nav-item:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }
        .nav-item.active {
            background: rgba(99,102,241,0.15);
            color: var(--accent-light);
            border-left: 3px solid var(--accent);
        }
        .nav-item .nav-icon {
            width: 28px; height: 28px;
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }
        .nav-number {
            margin-left: auto;
            font-size: 10px;
            background: var(--bg-hover);
            color: var(--text-muted);
            border-radius: 10px;
            padding: 1px 7px;
        }
        .nav-item.active .nav-number {
            background: rgba(99,102,241,0.3);
            color: var(--accent-light);
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            height: 64px;
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(12px);
        }
        .topbar .page-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .topbar .page-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-left: auto;
        }
        .content {
            flex: 1;
            padding: 28px;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .alert-success { background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #34d399; }
        .alert-danger  { background: rgba(239,68,68,0.15);  border: 1px solid rgba(239,68,68,0.3);  color: #f87171; }
        .alert-warning { background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.3); color: #fbbf24; }

        /* ===== CARDS ===== */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }
        .card-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--text-primary);
        }

        /* ===== FORMS ===== */
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-control {
            width: 100%;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            padding: 10px 14px;
            font-size: 14px;
            font-family: inherit;
            transition: var(--transition);
            outline: none;
        }
        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        .form-control::placeholder { color: var(--text-muted); }
        select.form-control option { background: var(--bg-secondary); }
        textarea.form-control { resize: vertical; min-height: 90px; }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: var(--transition);
            text-decoration: none;
            font-family: inherit;
        }
        .btn:active { transform: scale(0.97); }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #a855f7);
            color: white;
            box-shadow: 0 4px 15px rgba(99,102,241,0.3);
        }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(99,102,241,0.5); transform: translateY(-1px); }
        .btn-success { background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.3); color: #34d399; }
        .btn-success:hover { background: rgba(16,185,129,0.3); }
        .btn-danger  { background: rgba(239,68,68,0.15);  border: 1px solid rgba(239,68,68,0.3);  color: #f87171; }
        .btn-danger:hover  { background: rgba(239,68,68,0.3); }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-ghost { background: transparent; border: 1px solid var(--border); color: var(--text-secondary); }
        .btn-ghost:hover { background: var(--bg-hover); color: var(--text-primary); }

        /* ===== TABLE ===== */
        .table-wrap { overflow-x: auto; border-radius: var(--radius-sm); }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: var(--bg-secondary);
            padding: 10px 14px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        tbody td {
            padding: 12px 14px;
            font-size: 13px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        tbody tr:hover { background: var(--bg-hover); }
        tbody tr:last-child td { border-bottom: none; }

        /* ===== BADGE ===== */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-success { background: rgba(16,185,129,0.15); color: #34d399; }
        .badge-warning { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .badge-danger  { background: rgba(239,68,68,0.15);  color: #f87171; }
        .badge-info    { background: rgba(99,102,241,0.15); color: #818cf8; }

        /* ===== GRID HELPERS ===== */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .grid-auto { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
        .flex { display: flex; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; }
        .flex-center  { display: flex; align-items: center; gap: 10px; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mt-1 { margin-top: 8px; }
        .mt-2 { margin-top: 16px; }
        .mb-1 { margin-bottom: 8px; }
        .mb-2 { margin-bottom: 16px; }
        .text-muted { color: var(--text-muted); font-size: 13px; }
        .text-sm { font-size: 13px; }
        .w-full { width: 100%; }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--bg-hover); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
        }

        /* ===== STAT CARD ===== */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
        .stat-value { font-size: 24px; font-weight: 800; }
        .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('home') }}">
                <div class="logo">🚀</div>
                <div>
                    <div class="brand-name">Laravel MVC</div>
                    <div class="brand-sub">11 Ejercicios</div>
                </div>
            </a>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-label">Principal</div>
            <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <div class="nav-icon" style="background: rgba(99,102,241,0.15)">🏠</div>
                Dashboard
            </a>

            <div class="nav-section-label" style="margin-top:10px">Ejercicios</div>

            @php
            $navItems = [
                ['route'=>'tasks.index',       'icon'=>'✅','label'=>'Lista de Tareas',         'num'=>'01','color'=>'rgba(99,102,241,0.15)'],
                ['route'=>'tip.index',          'icon'=>'💰','label'=>'Calculadora Propinas',    'num'=>'02','color'=>'rgba(16,185,129,0.15)'],
                ['route'=>'password.index',     'icon'=>'🔐','label'=>'Generador Contraseñas',  'num'=>'03','color'=>'rgba(245,158,11,0.15)'],
                ['route'=>'expenses.index',     'icon'=>'💳','label'=>'Gestor de Gastos',        'num'=>'04','color'=>'rgba(239,68,68,0.15)'],
                ['route'=>'reservations.index', 'icon'=>'📅','label'=>'Sistema de Reservas',    'num'=>'05','color'=>'rgba(139,92,246,0.15)'],
                ['route'=>'notes.index',        'icon'=>'📝','label'=>'Gestor de Notas',         'num'=>'06','color'=>'rgba(6,182,212,0.15)'],
                ['route'=>'calendar.index',     'icon'=>'🗓️','label'=>'Calendario de Eventos',  'num'=>'07','color'=>'rgba(236,72,153,0.15)'],
                ['route'=>'recipes.index',      'icon'=>'🍳','label'=>'Plataforma Recetas',      'num'=>'08','color'=>'rgba(249,115,22,0.15)'],
                ['route'=>'memory.index',       'icon'=>'🃏','label'=>'Juego de Memoria',        'num'=>'09','color'=>'rgba(20,184,166,0.15)'],
                ['route'=>'surveys.index',      'icon'=>'📊','label'=>'Plataforma Encuestas',    'num'=>'10','color'=>'rgba(168,85,247,0.15)'],
                ['route'=>'stopwatch.index',    'icon'=>'⏱️','label'=>'Cronómetro Online',       'num'=>'11','color'=>'rgba(59,130,246,0.15)'],
            ];
            @endphp

            @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}" class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                <div class="nav-icon" style="background: {{ $item['color'] }}">{{ $item['icon'] }}</div>
                {{ $item['label'] }}
                <span class="nav-number">{{ $item['num'] }}</span>
            </a>
            @endforeach
        </nav>
    </aside>

    <!-- MAIN -->
    <div class="main">
        <div class="topbar">
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            <span class="page-subtitle">Laravel 13 &bull; MVC &bull; MySQL</span>
        </div>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fa fa-times-circle"></i> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
