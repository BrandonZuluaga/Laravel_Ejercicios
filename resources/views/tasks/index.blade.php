@extends('layouts.app')
@section('title', 'Lista de Tareas')
@section('page-title', '✅ Lista de Tareas')

@push('styles')
<style>
.task-item {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    margin-bottom: 10px;
    transition: var(--transition);
}
.task-item:hover { border-color: rgba(255,255,255,0.15); background: var(--bg-hover); }
.task-item.completed { opacity: 0.55; }
.task-item.completed .task-title { text-decoration: line-through; color: var(--text-muted); }

.task-check {
    width: 22px; height: 22px;
    border-radius: 6px;
    border: 2px solid var(--border);
    background: transparent;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: var(--transition);
}
.task-check.done { background: var(--success); border-color: var(--success); }
.task-check.done::after { content: '✓'; color: white; font-size: 13px; font-weight: 700; }

.task-title { font-size: 14px; font-weight: 500; color: var(--text-primary); }
.task-desc  { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
.task-date  { font-size: 11px; color: var(--text-muted); margin-left: auto; white-space: nowrap; }

.filter-tabs { display: flex; gap: 8px; margin-bottom: 20px; }
.filter-tab {
    padding: 7px 16px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    border: 1px solid var(--border);
    color: var(--text-secondary);
    transition: var(--transition);
}
.filter-tab.active, .filter-tab:hover {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}

.empty-state { text-align: center; padding: 50px 20px; color: var(--text-muted); }
.empty-state .empty-icon { font-size: 48px; margin-bottom: 12px; }
</style>
@endpush

@section('content')
<div class="grid-2">
    <!-- FORM -->
    <div>
        <div class="card">
            <div class="card-title">➕ Nueva Tarea</div>
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Título *</label>
                    <input type="text" name="title" class="form-control" placeholder="Ej: Estudiar para el examen" required value="{{ old('title') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Descripción (opcional)</label>
                    <textarea name="description" class="form-control" placeholder="Detalles adicionales...">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary w-full">
                    <i class="fa fa-plus"></i> Agregar Tarea
                </button>
            </form>
        </div>

        <!-- STATS -->
        <div class="grid-2 mt-2" style="gap:12px">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(99,102,241,0.15)">📋</div>
                <div><div class="stat-value">{{ $tasks->count() }}</div><div class="stat-label">Total</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(16,185,129,0.15)">✅</div>
                <div><div class="stat-value">{{ $tasks->where('completed',true)->count() }}</div><div class="stat-label">Completadas</div></div>
            </div>
        </div>
    </div>

    <!-- TASK LIST -->
    <div>
        <div class="filter-tabs">
            <a href="{{ route('tasks.index') }}" class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">Todas</a>
            <a href="{{ route('tasks.index', ['filter'=>'pending']) }}" class="filter-tab {{ $filter === 'pending' ? 'active' : '' }}">Pendientes</a>
            <a href="{{ route('tasks.index', ['filter'=>'completed']) }}" class="filter-tab {{ $filter === 'completed' ? 'active' : '' }}">Completadas</a>
        </div>

        @forelse($tasks as $task)
        <div class="task-item {{ $task->completed ? 'completed' : '' }}">
            <form action="{{ route('tasks.toggle', $task) }}" method="POST" style="margin:0">
                @csrf @method('PATCH')
                <button type="submit" class="task-check {{ $task->completed ? 'done' : '' }}" title="Toggle"></button>
            </form>
            <div style="flex:1">
                <div class="task-title">{{ $task->title }}</div>
                @if($task->description)
                <div class="task-desc">{{ $task->description }}</div>
                @endif
            </div>
            <span class="task-date">{{ $task->created_at->diffForHumans() }}</span>
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="margin:0" onsubmit="return confirm('¿Eliminar esta tarea?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
            </form>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <div>No hay tareas en esta categoría</div>
        </div>
        @endforelse
    </div>
</div>
@endsection
