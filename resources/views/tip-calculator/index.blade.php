@extends('layouts.app')
@section('title', 'Calculadora de Propinas')
@section('page-title', '💰 Calculadora de Propinas (COP)')

@push('styles')
<style>
.tip-buttons {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
    margin-top: 8px;
}
.tip-btn {
    padding: 10px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    background: var(--bg-hover);
    color: var(--text-primary);
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    text-align: center;
}
.tip-btn:hover, .tip-btn.active {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}
.result-box {
    background: linear-gradient(135deg, rgba(99,102,241,0.12) 0%, rgba(16,185,129,0.1) 100%);
    border: 1px solid rgba(99,102,241,0.25);
    border-radius: var(--radius);
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.result-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 12px;
    border-bottom: 1px dashed var(--border);
}
.result-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
    padding-top: 8px;
}
.result-val {
    font-size: 20px;
    font-weight: 700;
    color: var(--accent-light);
}
.result-val.total {
    font-size: 26px;
    font-weight: 800;
    color: var(--success);
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
<div class="grid-2">
    <!-- FORM CARD -->
    <div class="card">
        <div class="flex-between mb-3">
            <div class="card-title" style="margin:0;">💵 Datos de la Cuenta</div>
            <span class="currency-badge"><i class="fa fa-coins"></i> Pesos Colombianos (COP)</span>
        </div>
        <form action="{{ route('tip.calculate') }}" method="POST" id="tipForm">
            @csrf
            <div class="form-group">
                <label class="form-label">Total de la Cuenta ($ COP) *</label>
                <input type="number" step="100" min="100" name="amount" id="amountInput" class="form-control" placeholder="Ej: 85000" value="{{ old('amount', $amount ?? '80000') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Porcentaje de Propina (%) *</label>
                <div class="tip-buttons">
                    @foreach([10, 12, 15, 18, 20] as $pct)
                    <button type="button" class="tip-btn {{ (isset($tipPercent) && $tipPercent == $pct) || (!isset($tipPercent) && $pct == 10) ? 'active' : '' }}" onclick="selectTip({{ $pct }})">{{ $pct }}%</button>
                    @endforeach
                </div>
                <input type="number" step="0.5" min="0" max="100" name="tip" id="tipInput" class="form-control mt-2" placeholder="O ingresa un % personalizado" value="{{ old('tip', $tipPercent ?? 10) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Número de Personas *</label>
                <input type="number" min="1" max="100" name="people" id="peopleInput" class="form-control" value="{{ old('people', $people ?? 1) }}" required>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                <i class="fa fa-calculator"></i> Calcular Propina
            </button>
        </form>
    </div>

    <!-- RESULT CARD -->
    <div>
        <div class="card">
            <div class="card-title">🧾 Resumen de Cálculo</div>

            <div class="result-box">
                <div class="result-row">
                    <div>
                        <div style="font-weight:600; color:var(--text-primary)">Subtotal</div>
                        <small style="color:var(--text-muted)">Cuenta inicial</small>
                    </div>
                    <div class="result-val" id="resAmount">$ {{ number_format($amount ?? 80000, 0, ',', '.') }} COP</div>
                </div>

                <div class="result-row">
                    <div>
                        <div style="font-weight:600; color:var(--text-primary)">Propina Total (<span id="resTipPct">{{ $tipPercent ?? 10 }}</span>%)</div>
                        <small style="color:var(--text-muted)">Servicio voluntario sugerido</small>
                    </div>
                    <div class="result-val" id="resTip">$ {{ number_format($tipAmount ?? 8000, 0, ',', '.') }} COP</div>
                </div>

                <div class="result-row">
                    <div>
                        <div style="font-weight:600; color:var(--text-primary)">Propina por Persona</div>
                        <small style="color:var(--text-muted)">Dividido equitativamente</small>
                    </div>
                    <div class="result-val" id="resTipPerPerson">$ {{ number_format($tipPerPerson ?? 8000, 0, ',', '.') }} COP</div>
                </div>

                <div class="result-row">
                    <div>
                        <div style="font-weight:700; color:var(--text-primary); font-size:16px;">Total por Persona</div>
                        <small style="color:var(--text-muted)">Cuenta + propina / persona</small>
                    </div>
                    <div class="result-val" id="resPerPerson">$ {{ number_format($perPerson ?? 88000, 0, ',', '.') }} COP</div>
                </div>

                <div class="result-row">
                    <div>
                        <div style="font-weight:800; color:var(--text-primary); font-size:18px;">Total General</div>
                        <small style="color:var(--text-muted)">Total a cancelar</small>
                    </div>
                    <div class="result-val total" id="resTotal">$ {{ number_format($totalAmount ?? 88000, 0, ',', '.') }} COP</div>
                </div>
            </div>

            <div class="mt-3" style="font-size:12px; color:var(--text-muted); text-align:center;">
                ⚡ Moneda oficial: <strong>Pesos Colombianos (COP)</strong>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function formatCOP(val) {
    return '$ ' + Math.round(val).toLocaleString('es-CO') + ' COP';
}

function selectTip(percent) {
    document.getElementById('tipInput').value = percent;
    document.querySelectorAll('.tip-btn').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
    recalculateLive();
}

function recalculateLive() {
    const amount = parseFloat(document.getElementById('amountInput').value) || 0;
    const tip = parseFloat(document.getElementById('tipInput').value) || 0;
    const people = parseInt(document.getElementById('peopleInput').value) || 1;

    const tipAmount = amount * (tip / 100);
    const totalAmount = amount + tipAmount;
    const perPerson = totalAmount / people;
    const tipPerPerson = tipAmount / people;

    document.getElementById('resAmount').innerText = formatCOP(amount);
    document.getElementById('resTipPct').innerText = tip;
    document.getElementById('resTip').innerText = formatCOP(tipAmount);
    document.getElementById('resTipPerPerson').innerText = formatCOP(tipPerPerson);
    document.getElementById('resPerPerson').innerText = formatCOP(perPerson);
    document.getElementById('resTotal').innerText = formatCOP(totalAmount);
}

document.getElementById('amountInput').addEventListener('input', recalculateLive);
document.getElementById('tipInput').addEventListener('input', recalculateLive);
document.getElementById('peopleInput').addEventListener('input', recalculateLive);
</script>
@endpush
