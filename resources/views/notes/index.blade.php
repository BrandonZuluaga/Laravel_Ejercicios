@extends('layouts.app')
@section('title', 'Gestor de Notas')
@section('page-title', '📝 Gestor de Notas y Recordatorios')

@push('styles')
<style>
.notes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
}
.note-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-top: 4px solid var(--note-color, #6366f1);
    border-radius: var(--radius);
    padding: 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: var(--transition);
    position: relative;
}
.note-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
.note-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 8px;
}
.note-body {
    font-size: 13px;
    color: var(--text-secondary);
    line-height: 1.5;
    white-space: pre-wrap;
    margin-bottom: 16px;
}
.note-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid var(--border);
    padding-top: 12px;
}
.color-picker-group {
    display: flex;
    gap: 8px;
    margin-top: 6px;
}
.color-dot {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid transparent;
    transition: transform 0.2s;
}
.color-dot:hover, .color-dot.active {
    transform: scale(1.2);
    border-color: white;
}
</style>
@endpush

@section('content')
<!-- TOP SEARCH & FILTER -->
<div class="card mb-4">
    <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:center; justify-content:space-between;">
        <form method="GET" action="{{ route('notes.index') }}" style="display:flex; gap:10px; flex:1; max-width:500px;">
            <input type="text" name="search" class="form-control" placeholder="🔍 Buscar notas..." value="{{ $search }}">
            @if($category)
            <input type="hidden" name="category" value="{{ $category }}">
            @endif
            <button type="submit" class="btn btn-secondary">Buscar</button>
            @if($search || $category)
            <a href="{{ route('notes.index') }}" class="btn btn-secondary">Limpiar</a>
            @endif
        </form>

        <button type="button" class="btn btn-primary" onclick="toggleForm()">
            <i class="fa fa-plus"></i> Nueva Nota
        </button>
    </div>

    <!-- CATEGORIES ROW -->
    @if($categories->isNotEmpty())
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:16px;">
        <a href="{{ route('notes.index', ['search'=>$search]) }}" class="badge" style="background: {{ empty($category) ? 'var(--accent)' : 'var(--bg-hover)' }}; color:white; padding:6px 12px; text-decoration:none;">Todas</a>
        @foreach($categories as $cat)
        <a href="{{ route('notes.index', ['category'=>$cat, 'search'=>$search]) }}" class="badge" style="background: {{ $category === $cat ? 'var(--accent)' : 'var(--bg-hover)' }}; color:white; padding:6px 12px; text-decoration:none;">{{ $cat }}</a>
        @endforeach
    </div>
    @endif
</div>

<!-- CREATE NOTE FORM (Collapsible) -->
<div id="createNoteBox" class="card mb-4" style="display: {{ $errors->any() ? 'block' : 'none' }}; border: 1px solid var(--accent);">
    <div class="card-title">✍️ Escribir Nota</div>
    <form action="{{ route('notes.store') }}" method="POST">
        @csrf
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Título *</label>
                <input type="text" name="title" class="form-control" placeholder="Título de la nota..." required value="{{ old('title') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Categoría *</label>
                <input type="text" name="category" class="form-control" placeholder="Ej: Personal, Trabajo, Ideas..." required value="{{ old('category', 'General') }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Contenido *</label>
            <textarea name="content" class="form-control" rows="4" placeholder="Escribe aquí los detalles..." required>{{ old('content') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Color del Encabezado</label>
            <input type="hidden" name="color" id="noteColorInput" value="{{ old('color', '#6366f1') }}">
            <div class="color-picker-group">
                @foreach(['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#8b5cf6', '#06b6d4'] as $c)
                <div class="color-dot {{ old('color', '#6366f1') === $c ? 'active' : '' }}" style="background: {{ $c }};" onclick="pickColor('{{ $c }}', this)"></div>
                @endforeach
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar Nota</button>
            <button type="button" class="btn btn-secondary" onclick="toggleForm()">Cancelar</button>
        </div>
    </form>
</div>

<!-- NOTES GRID -->
@if($notes->isEmpty())
<div class="card" style="text-align:center; padding:50px 20px; color:var(--text-muted);">
    <div style="font-size:48px; margin-bottom:12px;">📑</div>
    <div>No se encontraron notas. ¡Crea tu primera nota ahora!</div>
</div>
@else
<div class="notes-grid">
    @foreach($notes as $note)
    <div class="note-card" style="--note-color: {{ $note->color }};">
        <div>
            <div class="note-title">{{ $note->title }}</div>
            <div class="note-body">{{ $note->content }}</div>
        </div>
        <div class="note-footer">
            <span class="badge" style="background: rgba(255,255,255,0.08); color: var(--text-secondary); font-size:11px;">
                {{ $note->category }}
            </span>
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:11px; color:var(--text-muted);">{{ $note->created_at->diffForHumans() }}</span>
                <form action="{{ route('notes.destroy', $note) }}" method="POST" onsubmit="return confirm('¿Eliminar esta nota?')" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection

@push('scripts')
<script>
function toggleForm() {
    const box = document.getElementById('createNoteBox');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

function pickColor(color, el) {
    document.getElementById('noteColorInput').value = color;
    document.querySelectorAll('.color-dot').forEach(d => d.classList.remove('active'));
    el.classList.add('active');
}
</script>
@endpush