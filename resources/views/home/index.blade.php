@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', '🚀 Dashboard')

@push('styles')
<style>
.hero {
    background: linear-gradient(135deg, rgba(99,102,241,0.15) 0%, rgba(168,85,247,0.1) 100%);
    border: 1px solid rgba(99,102,241,0.2);
    border-radius: 20px;
    padding: 40px;
    margin-bottom: 32px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: radial-gradient(circle at center, rgba(99,102,241,0.08) 0%, transparent 60%);
    animation: pulse 4s ease-in-out infinite;
}
@keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.05)} }
.hero h1 { font-size: 36px; font-weight: 800; margin-bottom: 10px; background: linear-gradient(135deg, #f0f0ff, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.hero p { font-size: 16px; color: var(--text-secondary); max-width: 500px; margin: 0 auto; }

.app-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 22px;
    text-decoration: none;
    display: block;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    cursor: pointer;
}
.app-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--card-color);
    transform: scaleX(0);
    transform-origin: left;
    transition: var(--transition);
}
.app-card:hover { transform: translateY(-4px); border-color: rgba(255,255,255,0.15); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
.app-card:hover::before { transform: scaleX(1); }
.app-card .app-icon { font-size: 32px; margin-bottom: 14px; display: block; }
.app-card .app-number {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 4px;
}
.app-card .app-title { font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; }
.app-card .app-desc  { font-size: 12px; color: var(--text-muted); line-height: 1.4; }
.app-card .app-arrow {
    position: absolute;
    top: 20px; right: 20px;
    color: var(--text-muted);
    font-size: 12px;
    transition: var(--transition);
}
.app-card:hover .app-arrow { color: var(--accent-light); transform: translateX(3px); }

.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 32px; }
.stat-mini { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius); padding: 16px 20px; text-align: center; }
.stat-mini .num { font-size: 28px; font-weight: 800; }
.stat-mini .lbl { font-size: 11px; color: var(--text-muted); font-weight: 500; margin-top: 2px; }

.section-title { font-size: 14px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
</style>
@endpush

@section('content')
<div class="hero">
    <h1>Laravel MVC Ejercicios</h1>
    <p>11 aplicaciones web construidas con arquitectura MVC, Laravel 13 y MySQL</p>
</div>

<div class="stats-row">
    <div class="stat-mini"><div class="num" style="color:#6366f1">11</div><div class="lbl">Ejercicios</div></div>
    <div class="stat-mini"><div class="num" style="color:#10b981">MVC</div><div class="lbl">Arquitectura</div></div>
    <div class="stat-mini"><div class="num" style="color:#f59e0b">13</div><div class="lbl">Laravel v.</div></div>
    <div class="stat-mini"><div class="num" style="color:#a855f7">MySQL</div><div class="lbl">Base de Datos</div></div>
</div>

<div class="section-title">Todas las Aplicaciones</div>
<div class="grid-auto">
    @foreach($apps as $i => $app)
    <a href="{{ route($app['route']) }}" class="app-card" style="--card-color: {{ $app['color'] }}">
        <span class="app-icon">{{ $app['icon'] }}</span>
        <div class="app-number">Ejercicio {{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</div>
        <div class="app-title">{{ $app['title'] }}</div>
        <div class="app-desc">{{ $app['desc'] }}</div>
        <i class="fa fa-arrow-right app-arrow"></i>
    </a>
    @endforeach
</div>
@endsection
