@extends('layouts.app')
@section('title', 'Calendario de Eventos')
@section('page-title', '📆 Calendario de Eventos')

@push('styles')
<style>
.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.calendar-nav-btn {
    background: var(--bg-card);
    border: 1px solid var(--border);
    color: var(--text-primary);
    padding: 8px 16px;
    border-radius: var(--radius-sm);
    text-decoration: none;
    font-weight: 600;
    font-size: 13px;
    transition: var(--transition);
}
.calendar-nav-btn:hover {
    background: var(--accent);
    color: white;
}
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
}
.calendar-day-header {
    text-align: center;
    font-weight: 700;
    font-size: 12px;
    color: var(--text-muted);
    padding: 10px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.calendar-day {
    min-height: 100px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 8px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    transition: var(--transition);
}
.calendar-day:hover {
    border-color: rgba(255,255,255,0.2);
}
.calendar-day.empty {
    background: transparent;
    border-color: transparent;
}
.calendar-day.today {
    border-color: var(--accent);
    background: rgba(99,102,241,0.05);
}
.day-number {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-secondary);
    margin-bottom: 4px;
}
.calendar-day.today .day-number {
    color: var(--accent-light);
}
.calendar-event-tag {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 6px;
    border-radius: 4px;
    color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
}
</style>
@endpush

@section('content')
<div class="grid-3 mb-4" style="grid-template-columns: 2fr 1fr;">
    <!-- CALENDAR CARD -->
    <div class="card">
        <!-- MONTH NAVIGATION -->
        <div class="calendar-header">
            <a href="{{ route('calendar.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" class="calendar-nav-btn">
                <i class="fa fa-chevron-left"></i> {{ $prevMonth->translatedFormat('M Y') }}
            </a>
            <h2 style="font-size: 20px; font-weight: 800; color: var(--text-primary); margin: 0; text-transform: capitalize;">
                {{ $date->translatedFormat('F Y') }}
            </h2>
            <a href="{{ route('calendar.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" class="calendar-nav-btn">
                {{ $nextMonth->translatedFormat('M Y') }} <i class="fa fa-chevron-right"></i>
            </a>
        </div>

        <!-- DAYS HEADER -->
        <div class="calendar-grid">
            <div class="calendar-day-header">Dom</div>
            <div class="calendar-day-header">Lun</div>
            <div class="calendar-day-header">Mar</div>
            <div class="calendar-day-header">Mié</div>
            <div class="calendar-day-header">Jue</div>
            <div class="calendar-day-header">Vie</div>
            <div class="calendar-day-header">Sáb</div>

            <!-- EMPTY DAYS BEFORE FIRST DAY -->
            @for($i = 0; $i < $firstDay; $i++)
            <div class="calendar-day empty"></div>
            @endfor

            <!-- DAYS OF MONTH -->
            @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $currentDayStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $isToday = $currentDayStr === now()->toDateString();
                $dayEvents = $events->filter(function($e) use ($currentDayStr) {
                    return substr($e->start_date, 0, 10) === $currentDayStr;
                });
            @endphp
            <div class="calendar-day {{ $isToday ? 'today' : '' }}">
                <div class="day-number">{{ $day }}</div>
                @foreach($dayEvents as $evt)
                <div class="calendar-event-tag" style="background: {{ $evt->color }};" title="{{ $evt->title }} - {{ $evt->description }}">
                    <span>{{ $evt->title }}</span>
                    <form action="{{ route('calendar.destroy', $evt) }}" method="POST" onsubmit="return confirm('¿Eliminar evento?')" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none; border:none; color:white; cursor:pointer; font-size:10px; padding:0 2px;">×</button>
                    </form>
                </div>
                @endforeach
            </div>
            @endfor
        </div>
    </div>

    <!-- ADD EVENT FORM & UPCOMING -->
    <div>
        <div class="card mb-4">
            <div class="card-title">➕ Agregar Evento</div>
            <form action="{{ route('calendar.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Título *</label>
                    <input type="text" name="title" class="form-control" placeholder="Ej: Cumpleaños, Reunión..." required value="{{ old('title') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Fecha *</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', now()->toDateString()) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Hora (Opcional)</label>
                    <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="description" class="form-control" placeholder="Detalles del evento...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Color de Etiqueta</label>
                    <select name="color" class="form-control">
                        <option value="#6366f1" style="background:#6366f1; color:white;">Índigo</option>
                        <option value="#10b981" style="background:#10b981; color:white;">Verde</option>
                        <option value="#f59e0b" style="background:#f59e0b; color:white;">Naranja</option>
                        <option value="#ef4444" style="background:#ef4444; color:white;">Rojo</option>
                        <option value="#8b5cf6" style="background:#8b5cf6; color:white;">Púrpura</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    <i class="fa fa-calendar-plus"></i> Guardar Evento
                </button>
            </form>
        </div>

        <!-- UPCOMING EVENTS LIST -->
        <div class="card">
            <div class="card-title">📌 Eventos de Este Mes ({{ $events->count() }})</div>
            @if($events->isEmpty())
            <div style="font-size:12px; color:var(--text-muted); text-align:center; padding:15px 0;">
                No hay eventos este mes.
            </div>
            @else
            <div style="display:flex; flex-direction:column; gap:8px;">
                @foreach($events as $e)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 12px; background:var(--bg-hover); border-left:3px solid {{ $e->color }}; border-radius:4px;">
                    <div>
                        <div style="font-weight:700; font-size:13px;">{{ $e->title }}</div>
                        <div style="font-size:11px; color:var(--text-muted);">
                            {{ \Carbon\Carbon::parse($e->start_date)->format('d/m/Y') }} {{ $e->start_time ? '- '.substr($e->start_time,0,5) : '' }}
                        </div>
                    </div>
                    <form action="{{ route('calendar.destroy', $e) }}" method="POST" onsubmit="return confirm('¿Eliminar evento?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" style="padding:2px 8px;"><i class="fa fa-trash"></i></button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection