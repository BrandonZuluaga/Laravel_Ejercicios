@extends('layouts.app')
@section('title', 'Plataforma de Recetas')
@section('page-title', '🍳 Plataforma de Recetas de Cocina')

@push('styles')
<style>
.recipes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}
.recipe-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: var(--transition);
}
.recipe-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.25);
    border-color: rgba(255,255,255,0.2);
}
.recipe-cat-badge {
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
    background: rgba(99,102,241,0.15);
    color: var(--accent-light);
}
.diff-badge {
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
}
.diff-Fácil { background: rgba(16,185,129,0.15); color: #10b981; }
.diff-Media { background: rgba(245,158,11,0.15); color: #f59e0b; }
.diff-Difícil { background: rgba(239,68,68,0.15); color: #ef4444; }
.meta-info {
    display: flex;
    gap: 12px;
    font-size: 12px;
    color: var(--text-muted);
    margin: 12px 0 16px 0;
}
</style>
@endpush

@section('content')
<!-- TOP CONTROLS -->
<div class="card mb-4">
    <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center; justify-content:space-between;">
        <form method="GET" action="{{ route('recipes.index') }}" style="display:flex; gap:10px; flex:1; max-width:500px;">
            <input type="text" name="search" class="form-control" placeholder="🔍 Buscar por nombre o ingrediente..." value="{{ $search }}">
            @if($category)
            <input type="hidden" name="category" value="{{ $category }}">
            @endif
            <button type="submit" class="btn btn-secondary">Buscar</button>
            @if($search || $category)
            <a href="{{ route('recipes.index') }}" class="btn btn-secondary">Limpiar</a>
            @endif
        </form>

        <button type="button" class="btn btn-primary" onclick="toggleRecipeForm()">
            <i class="fa fa-plus"></i> Publicar Receta
        </button>
    </div>

    <!-- CATEGORIES -->
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:16px;">
        <a href="{{ route('recipes.index', ['search'=>$search]) }}" class="badge" style="background: {{ empty($category) ? 'var(--accent)' : 'var(--bg-hover)' }}; color:white; padding:6px 12px; text-decoration:none;">Todas</a>
        @foreach($categories as $cat)
        <a href="{{ route('recipes.index', ['category'=>$cat, 'search'=>$search]) }}" class="badge" style="background: {{ $category === $cat ? 'var(--accent)' : 'var(--bg-hover)' }}; color:white; padding:6px 12px; text-decoration:none;">{{ $cat }}</a>
        @endforeach
    </div>
</div>

<!-- CREATE RECIPE FORM (Collapsible) -->
<div id="recipeFormBox" class="card mb-4" style="display: {{ $errors->any() ? 'block' : 'none' }}; border: 1px solid var(--accent);">
    <div class="card-title">🍲 Nueva Receta de Cocina</div>
    <form action="{{ route('recipes.store') }}" method="POST">
        @csrf
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Título de la Receta *</label>
                <input type="text" name="title" class="form-control" placeholder="Ej: Pasta Alfredo Clásica" required value="{{ old('title') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Categoría *</label>
                <select name="category" class="form-control" required>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid-3">
            <div class="form-group">
                <label class="form-label">Tiempo (minutos) *</label>
                <input type="number" min="1" name="prep_time" class="form-control" placeholder="30" required value="{{ old('prep_time', 30) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Porciones *</label>
                <input type="number" min="1" name="servings" class="form-control" placeholder="4" required value="{{ old('servings', 4) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Dificultad *</label>
                <select name="difficulty" class="form-control" required>
                    <option value="Fácil">Fácil</option>
                    <option value="Media" selected>Media</option>
                    <option value="Difícil">Difícil</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Ingredientes (un ingrediente por línea o separados por comas) *</label>
            <textarea name="ingredients" class="form-control" rows="4" placeholder="200g pasta, 1 taza crema, queso parmesano..." required>{{ old('ingredients') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Instrucciones de Preparación *</label>
            <textarea name="instructions" class="form-control" rows="5" placeholder="Paso 1: Hervir agua...&#10;Paso 2: Cocinar la pasta..." required>{{ old('instructions') }}</textarea>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Publicar Receta</button>
            <button type="button" class="btn btn-secondary" onclick="toggleRecipeForm()">Cancelar</button>
        </div>
    </form>
</div>

<!-- RECIPES LIST -->
@if($recipes->isEmpty())
<div class="card" style="text-align:center; padding:50px 20px; color:var(--text-muted);">
    <div style="font-size:48px; margin-bottom:12px;">🥘</div>
    <div>No se encontraron recetas con los filtros aplicados.</div>
</div>
@else
<div class="recipes-grid">
    @foreach($recipes as $recipe)
    <div class="recipe-card">
        <div>
            <div class="flex-between mb-2">
                <span class="recipe-cat-badge">{{ $recipe->category }}</span>
                <span class="diff-badge diff-{{ $recipe->difficulty }}">{{ $recipe->difficulty }}</span>
            </div>
            <h3 style="font-size:17px; font-weight:700; color:var(--text-primary); margin-bottom:6px;">{{ $recipe->title }}</h3>
            <div class="meta-info">
                <span><i class="fa fa-clock"></i> {{ $recipe->prep_time }} mins</span>
                <span><i class="fa fa-utensils"></i> {{ $recipe->servings }} porciones</span>
            </div>
            <p style="font-size:13px; color:var(--text-secondary); line-height:1.4; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;">
                {{ Str::limit($recipe->instructions, 120) }}
            </p>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:16px; border-top:1px solid var(--border); padding-top:12px;">
            <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-eye"></i> Ver Receta
            </a>
            <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('¿Eliminar esta receta?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection

@push('scripts')
<script>
function toggleRecipeForm() {
    const box = document.getElementById('recipeFormBox');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}
</script>
@endpush