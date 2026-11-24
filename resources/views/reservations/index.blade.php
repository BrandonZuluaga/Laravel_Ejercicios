@extends('layouts.app')
@section('title', 'Sistema de Reservas')
@section('page-title', '📅 Sistema de Reservas de Citas')

@push('styles')
<style>
.time-slots-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-top: 8px;
}
.time-slot {
    padding: 8px 6px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    background: var(--bg-hover);
    color: var(--text-primary);
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
}
.time-slot:hover:not(.booked) {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}
.time-slot.selected {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}
.time-slot.booked {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
    color: var(--text-muted);
    cursor: not-allowed;
    text-decoration: line-through;
}
.status-badge {
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}
.status-confirmed { background: rgba(16,185,129,0.15); color: #10b981; }
.status-cancelled { background: rgba(239,68,68,0.15); color: #ef4444; }
</style>
@endpush

@section('content')
<div class="grid-2">
    <!-- BOOKING FORM -->
    <div>
        <div class="card mb-4">
            <div class="card-title">📝 Agendar Nueva Cita</div>
            <form action="{{ route('reservations.store') }}" method="POST" id="reservationForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nombre Completo *</label>
                    <input type="text" name="name" class="form-control" placeholder="Ej: Carlos Gómez" required value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Correo Electrónico *</label>
                    <input type="email" name="email" class="form-control" placeholder="carlos@correo.com" required value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Servicio a Reservar *</label>
                    <select name="service" class="form-control" required>
                        <option value="">Seleccione un servicio...</option>
                        @foreach($services as $serv)
                        <option value="{{ $serv }}" {{ old('service') == $serv ? 'selected' : '' }}>{{ $serv }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Fecha de la Cita *</label>
                    <input type="date" name="date" id="dateInput" class="form-control" min="{{ now()->toDateString() }}" value="{{ old('date', $date) }}" required onchange="filterDate(this.value)">
                </div>

                <div class="form-group">
                    <label class="form-label">Horario Disponible (Selecciona uno) *</label>
                    <input type="hidden" name="time" id="selectedTime" value="{{ old('time') }}" required>
                    <div class="time-slots-grid">
                        @foreach($allTimes as $slot)
                        @php $isBooked = in_array($slot, $bookedTimes); @endphp
                        <button type="button" 
                                class="time-slot {{ $isBooked ? 'booked' : '' }} {{ old('time') == $slot ? 'selected' : '' }}" 
                                {{ $isBooked ? 'disabled' : '' }} 
                                onclick="pickTime('{{ $slot }}', this)">
                            {{ $slot }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Comentarios o Indicaciones (Opcional)</label>
                    <textarea name="notes" class="form-control" placeholder="Alergias, solicitudes especiales...">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    <i class="fa fa-calendar-check"></i> Confirmar Reserva
                </button>
            </form>
        </div>
    </div>

    <!-- RESERVATIONS LIST -->
    <div>
        <div class="card mb-4">
            <div class="flex-between mb-3">
                <div class="card-title" style="margin:0;">📅 Citas para el {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</div>
                <span class="badge" style="background:var(--accent); color:white;">{{ $reservations->where('status', 'confirmed')->count() }} confirmadas</span>
            </div>

            @if($reservations->isEmpty())
            <div style="text-align:center; color:var(--text-muted); padding:30px 0;">
                No hay reservas para esta fecha
            </div>
            @else
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($reservations as $res)
                <div style="padding:14px; background:var(--bg-hover); border:1px solid var(--border); border-radius:var(--radius-sm);">
                    <div class="flex-between">
                        <div>
                            <span style="font-weight:700; color:var(--text-primary);">{{ $res->name }}</span>
                            <span style="font-size:12px; color:var(--text-muted); margin-left:6px;">({{ $res->email }})</span>
                        </div>
                        <span class="status-badge status-{{ $res->status }}">{{ $res->status }}</span>
                    </div>
                    <div class="flex-between mt-2">
                        <div style="font-size:13px; color:var(--accent-light);">
                            <i class="fa fa-briefcase"></i> {{ $res->service }} | <i class="fa fa-clock"></i> {{ substr($res->time, 0, 5) }}
                        </div>
                        @if($res->status === 'confirmed')
                        <form action="{{ route('reservations.destroy', $res) }}" method="POST" onsubmit="return confirm('¿Cancelar esta reserva?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                        </form>
                        @endif
                    </div>
                    @if($res->notes)
                    <div style="font-size:12px; color:var(--text-muted); margin-top:6px; font-style:italic;">
                        Nota: {{ $res->notes }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- UPCOMING LIST -->
        <div class="card">
            <div class="card-title">🗓️ Próximas Reservas Globales</div>
            @if($allReservations->isEmpty())
            <div style="text-align:center; color:var(--text-muted); padding:20px 0;">
                No hay reservas programadas próximamente
            </div>
            @else
            <div style="display:flex; flex-direction:column; gap:8px;">
                @foreach($allReservations->take(5) as $res)
                <div style="display:flex; align-items:center; justify-content:space-between; font-size:13px; padding:8px 12px; background:var(--bg-hover); border-radius:var(--radius-sm);">
                    <div>
                        <strong>{{ $res->name }}</strong> - {{ $res->service }}
                        <div style="font-size:11px; color:var(--text-muted);">{{ \Carbon\Carbon::parse($res->date)->format('d/m/Y') }} a las {{ substr($res->time, 0, 5) }}</div>
                    </div>
                    <span class="status-badge status-{{ $res->status }}">{{ $res->status }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function pickTime(time, el) {
    document.getElementById('selectedTime').value = time;
    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
}

function filterDate(date) {
    window.location.href = "{{ route('reservations.index') }}?date=" + date;
}
</script>
@endpush