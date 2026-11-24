@extends('layouts.app')
@section('title', 'Generador de Contraseñas')
@section('page-title', '🔑 Generador de Contraseñas Seguras')

@push('styles')
<style>
.password-display-box {
    display: flex;
    align-items: center;
    background: var(--bg-hover);
    border: 2px dashed var(--accent);
    border-radius: var(--radius);
    padding: 16px 20px;
    margin-bottom: 24px;
    gap: 12px;
}
.password-text {
    flex: 1;
    font-family: 'Courier New', Courier, monospace;
    font-size: 22px;
    font-weight: 700;
    color: var(--accent-light);
    word-break: break-all;
    user-select: all;
}
.slider-container {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}
.slider-container input[type=range] {
    flex: 1;
    accent-color: var(--accent);
    cursor: pointer;
}
.slider-val {
    font-weight: 800;
    font-size: 18px;
    min-width: 32px;
    text-align: right;
    color: var(--accent-light);
}
.checkbox-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 24px;
}
.check-card {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--bg-hover);
    padding: 12px 16px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
}
.check-card input[type=checkbox] {
    width: 18px;
    height: 18px;
    accent-color: var(--accent);
    cursor: pointer;
}
.strength-meter {
    height: 8px;
    border-radius: 4px;
    background: var(--border);
    margin: 12px 0 20px 0;
    overflow: hidden;
}
.strength-fill {
    height: 100%;
    transition: width 0.3s ease, background-color 0.3s ease;
}
</style>
@endpush

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <div class="card">
        <div class="card-title">🔐 Crear Contraseña Robusta</div>

        <!-- PASSWORD RESULT -->
        <div class="password-display-box">
            <div class="password-text" id="passwordOutput">{{ $password ?? 'P@$$w0rd-G3n3r@t0r!' }}</div>
            <button type="button" class="btn btn-secondary" onclick="copyPassword()" title="Copiar al portapapeles" id="copyBtn">
                <i class="fa fa-copy"></i> Copiar
            </button>
        </div>

        <!-- STRENGTH INDICATOR -->
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size:13px; color:var(--text-secondary);">Seguridad estimada:</span>
            <span style="font-size:13px; font-weight:700; color: {{ $strengthLabel['color'] ?? '#10b981' }};" id="strengthText">
                {{ $strengthLabel['label'] ?? 'Fuerte' }}
            </span>
        </div>
        <div class="strength-meter">
            <div class="strength-fill" id="strengthFill" style="width: {{ isset($strength) ? min(100, ($strength / 6) * 100) : 85 }}%; background-color: {{ $strengthLabel['color'] ?? '#10b981' }};"></div>
        </div>

        <form action="{{ route('password.generate') }}" method="POST" id="passwordForm">
            @csrf

            <!-- LENGTH SLIDER -->
            <label class="form-label">Longitud de la contraseña</label>
            <div class="slider-container">
                <input type="range" name="length" id="lengthRange" min="8" max="64" value="{{ old('length', $length ?? 16) }}" oninput="document.getElementById('lengthVal').innerText = this.value">
                <div class="slider-val" id="lengthVal">{{ old('length', $length ?? 16) }}</div>
            </div>

            <!-- OPTIONS -->
            <label class="form-label">Opciones de caracteres</label>
            <div class="checkbox-grid">
                <label class="check-card">
                    <input type="checkbox" name="uppercase" value="1" {{ !isset($password) || !empty($uppercase) ? 'checked' : '' }}>
                    <span>Mayúsculas (A-Z)</span>
                </label>
                <label class="check-card">
                    <input type="checkbox" name="lowercase" value="1" {{ !isset($password) || !empty($lowercase) ? 'checked' : '' }}>
                    <span>Minúsculas (a-z)</span>
                </label>
                <label class="check-card">
                    <input type="checkbox" name="numbers" value="1" {{ !isset($password) || !empty($numbers) ? 'checked' : '' }}>
                    <span>Números (0-9)</span>
                </label>
                <label class="check-card">
                    <input type="checkbox" name="symbols" value="1" {{ !isset($password) || !empty($symbols) ? 'checked' : '' }}>
                    <span>Símbolos (!@#$...)</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                <i class="fa fa-sync-alt"></i> Generar Nueva Contraseña
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyPassword() {
    const text = document.getElementById('passwordOutput').innerText;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById('copyBtn');
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-check"></i> ¡Copiado!';
        btn.style.background = 'var(--success)';
        btn.style.color = '#fff';
        setTimeout(() => {
            btn.innerHTML = orig;
            btn.style.background = '';
            btn.style.color = '';
        }, 2000);
    });
}
</script>
@endpush