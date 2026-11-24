@extends('layouts.app')
@section('title', $recipe->title)
@section('page-title', '🍲 ' . $recipe->title)

@push('styles')
<style>
.recipe-header-card {
    background: linear-gradient(135deg, rgba(99,102,241,0.12) 0%, rgba(245,158,11,0.1) 100%);
    border: 1px solid rgba(99,102,241,0.2);
    border-radius: var(--radius);
    padding: 24px;
    margin-bottom: 24px;
}
.ingredient-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    background: var(--bg-hover);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    margin-bottom: 8px;
    font-size: 14px;
}
.instruction-step {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
    line-height: 1.6;
}
.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--accent);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    flex-shrink: 0;
}
</style>
@endpush

@section('content')
<div class="mb-3">
    <a href="{{ route('recipes.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa fa-arrow-left"></i> Volver a Recetas
    </a>
</div>

<div class="recipe-header-card">
    <div style="display:flex; gap:10px; margin-bottom:12px;">
        <span class="badge" style="background:var(--accent); color:white;">{{ $recipe->category }}</span>
        <span class="badge" style="background:rgba(255,255,255,0.1); color:var(--text-secondary);">Dificultad: {{ $recipe->difficulty }}</span>
    </div>
    <h1 style="font-size:28px; font-weight:800; color:var(--text-primary); margin-bottom:12px;">{{ $recipe->title }}</h1>
    <div style="display:flex; gap:20px; font-size:14px; color:var(--text-secondary);">
        <span><i class="fa fa-clock"></i> <strong>Tiempo:</strong> {{ $recipe->prep_time }} minutos</span>
        <span><i class="fa fa-utensils"></i> <strong>Porciones:</strong> {{ $recipe->servings }}</span>
        <span><i class="fa fa-calendar"></i> <strong>Publicado:</strong> {{ $recipe->created_at->format('d/m/Y') }}</span>
    </div>
</div>

<div class="grid-2">
    <!-- INGREDIENTS -->
    <div class="card">
        <div class="card-title">🛒 Ingredientes Requeridos</div>
        @php
            $ingList = preg_split('/[\r\n,]+/', $recipe->ingredients);
        @endphp
        @foreach($ingList as $ing)
            @if(trim($ing))
            <label class="ingredient-item" style="cursor:pointer;">
                <input type="checkbox" style="width:18px; height:18px; accent-color:var(--accent);">
                <span>{{ trim($ing) }}</span>
            </label>
            @endif
        @endforeach
    </div>

    <!-- INSTRUCTIONS -->
    <div class="card">
        <div class="card-title">📖 Preparación Paso a Paso</div>
        @php
            $steps = preg_split('/[\r\n]+/', $recipe->instructions);
            $stepCounter = 1;
        @endphp
        @foreach($steps as $step)
            @if(trim($step))
            <div class="instruction-step">
                <div class="step-number">{{ $stepCounter++ }}</div>
                <div style="color:var(--text-primary); font-size:14px; padding-top:4px;">{{ trim($step) }}</div>
            </div>
            @endif
        @endforeach
    </div>
</div>
@endsection