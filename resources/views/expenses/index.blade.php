@extends('layouts.app')
@section('title', 'Gestor de Gastos')
@section('page-title', '📊 Gestor de Gastos Diarios (COP)')

@push('styles')
<style>
.category-pill {
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 600;
    background: rgba(99,102,241,0.15);
    color: var(--accent-light);
    display: inline-block;
}
.cat-progress {
    height: 6px;
    background: var(--border);
    border-radius: 3px;
    overflow: hidden;
    margin-top: 6px;
}
.cat-progress-fill {
    height: 100%;
    background: var(--accent);
    border-radius: 3px;
}
.month-selector {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--bg-card);
    padding: 10px 18px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
}
.currency-badge {
    background: rgba(16,185,129,0.15);
    color: var(--success);
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 50px;
    display: inline-block;
}
</style>
@endpush

@section('content')
<div class="flex-between mb-4">
    <div>
        <div style="font-size: 18px; font-weight: 700;">
            Resumen Mensual: <span style="color:var(--accent-light)">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}</span>
        </div>
        <span class="currency-badge mt-1"><i class="fa fa-coins"></i> Pesos Colombianos (COP)</span>
    </div>
    <form method="GET" action="{{ route('expenses.index') }}" class="month-selector">
        <label for="monthInput" style="font-size:13px; font-weight:600; color:var(--text-secondary); margin:0;">Mes:</label>
        <input type="month" id="monthInput" name="month" value="{{ $month }}" class="form-control" style="width: auto; padding: 6px 12px;" onchange="this.form.submit()">
    </form>
</div>

<!-- STATS ROW -->
<div class="grid-3 mb-4">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(239,68,68,0.15); color:#ef4444;">💸</div>
        <div>
            <div class="stat-value">$ {{ number_format($totalMonth, 0, ',', '.') }} <span style="font-size:14px; font-weight:600; color:var(--text-muted);">COP</span></div>
            <div class="stat-label">Gasto Total del Mes</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(99,102,241,0.15); color:var(--accent-light);">📝</div>
        <div>
            <div class="stat-value">{{ $expenses->count() }}</div>
            <div class="stat-label">Transacciones Registradas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(16,185,129,0.15); color:#10b981;">🏷️</div>
        <div>
            <div class="stat-value" style="font-size:18px; line-height:28px;">{{ $summary->first()->category ?? 'Sin datos' }}</div>
            <div class="stat-label">Categoría Principal</div>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- REGISTRATION FORM -->
    <div>
        <div class="card mb-4">
            <div class="card-title">➕ Registrar Nuevo Gasto</div>
            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Monto ($ COP) *</label>
                    <input type="number" step="100" min="100" name="amount" class="form-control" placeholder="Ej: 45000" required value="{{ old('amount') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Categoría *</label>
                    <select name="category" class="form-control" required>
                        <option value="">Selecciona una categoría...</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción *</label>
                    <input type="text" name="description" class="form-control" placeholder="Ej: Almuerzo de trabajo, Mercado, Gasolina..." required value="{{ old('description') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Fecha *</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', now()->toDateString()) }}" required>
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    <i class="fa fa-plus-circle"></i> Guardar Gasto
                </button>
            </form>
        </div>

        <!-- CATEGORY SUMMARY -->
        <div class="card">
            <div class="card-title">📈 Desglose por Categoría</div>
            @forelse($summary as $item)
            @php $pct = $totalMonth > 0 ? ($item->total / $totalMonth) * 100 : 0; @endphp
            <div class="mb-3">
                <div class="flex-between" style="font-size:13px; font-weight:600;">
                    <span>{{ $item->category }}</span>
                    <span>$ {{ number_format($item->total, 0, ',', '.') }} COP ({{ round($pct) }}%)</span>
                </div>
                <div class="cat-progress">
                    <div class="cat-progress-fill" style="width: {{ $pct }}%;"></div>
                </div>
            </div>
            @empty
            <div style="text-align:center; color:var(--text-muted); padding:20px 0;">
                No hay gastos registrados este mes
            </div>
            @endforelse
        </div>
    </div>

    <!-- EXPENSES LIST -->
    <div class="card">
        <div class="card-title">📋 Movimientos del Mes</div>
        @if($expenses->isEmpty())
        <div style="text-align:center; color:var(--text-muted); padding:50px 20px;">
            <div style="font-size:40px; margin-bottom:10px;">🧾</div>
            <div>No hay gastos registrados para este mes</div>
        </div>
        @else
        <div style="display:flex; flex-direction:column; gap:10px;">
            @foreach($expenses as $expense)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; background:var(--bg-hover); border:1px solid var(--border); border-radius:var(--radius-sm);">
                <div>
                    <div style="font-weight:600; color:var(--text-primary); font-size:14px;">{{ $expense->description }}</div>
                    <div style="display:flex; align-items:center; gap:8px; margin-top:4px;">
                        <span class="category-pill">{{ $expense->category }}</span>
                        <span style="font-size:12px; color:var(--text-muted);">{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</span>
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:16px;">
                    <span style="font-weight:700; font-size:15px; color:#ef4444;">-$ {{ number_format($expense->amount, 0, ',', '.') }} COP</span>
                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('¿Eliminar este gasto?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"><i class="fa fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
