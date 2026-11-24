@extends('layouts.app')
@section('title', 'Cronómetro Online')
@section('page-title', '⏱️ Cronómetro Online')

@push('styles')
<style>
.stopwatch-container {
    max-width: 650px;
    margin: 0 auto;
    text-align: center;
}
.display-circle {
    width: 280px;
    height: 280px;
    border-radius: 50%;
    border: 4px solid var(--accent);
    margin: 30px auto;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, rgba(15,23,42,0.6) 100%);
    box-shadow: 0 0 40px rgba(99,102,241,0.25);
    position: relative;
}
.time-display {
    font-family: 'Courier New', Courier, monospace;
    font-size: 38px;
    font-weight: 800;
    color: var(--text-primary);
    letter-spacing: 2px;
}
.ms-display {
    font-size: 20px;
    color: var(--accent-light);
    font-weight: 700;
    margin-top: 4px;
}
.controls-row {
    display: flex;
    justify-content: center;
    gap: 14px;
    margin-bottom: 30px;
}
.ctrl-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}
.ctrl-btn:hover {
    transform: scale(1.1);
}
.ctrl-btn.start { background: var(--success); color: white; }
.ctrl-btn.pause { background: #f59e0b; color: white; }
.ctrl-btn.lap   { background: var(--accent); color: white; }
.ctrl-btn.reset { background: var(--bg-hover); border: 1px solid var(--border); color: var(--text-secondary); }

.laps-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    text-align: left;
    max-height: 320px;
    overflow-y: auto;
}
.lap-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 14px;
    border-bottom: 1px solid var(--border);
    font-size: 14px;
    font-family: 'Courier New', Courier, monospace;
}
.lap-item:last-child { border-bottom: none; }
.lap-fastest { color: #10b981; font-weight: 700; }
.lap-slowest { color: #ef4444; font-weight: 700; }
</style>
@endpush

@section('content')
<div class="stopwatch-container">
    <div class="card mb-4">
        <!-- CIRCLE DISPLAY -->
        <div class="display-circle">
            <div class="time-display" id="timeMain">00:00:00</div>
            <div class="ms-display" id="timeMs">.00</div>
        </div>

        <!-- BUTTONS -->
        <div class="controls-row">
            <button type="button" class="ctrl-btn reset" id="btnReset" onclick="resetTimer()" title="Reiniciar">
                <i class="fa fa-undo"></i>
            </button>
            <button type="button" class="ctrl-btn start" id="btnStart" onclick="startTimer()" title="Iniciar">
                <i class="fa fa-play"></i>
            </button>
            <button type="button" class="ctrl-btn pause" id="btnPause" onclick="pauseTimer()" title="Pausar" style="display:none;">
                <i class="fa fa-pause"></i>
            </button>
            <button type="button" class="ctrl-btn lap" id="btnLap" onclick="recordLap()" title="Registrar Vuelta" disabled>
                <i class="fa fa-flag"></i>
            </button>
        </div>
    </div>

    <!-- LAPS LIST -->
    <div class="laps-card" id="lapsCard" style="display:none;">
        <div class="flex-between mb-3">
            <div style="font-weight:700; font-size:15px; color:var(--text-primary);">🚩 Tiempos Parciales / Vueltas</div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="clearLaps()">Limpiar Vueltas</button>
        </div>
        <div id="lapsList"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let startTime = 0;
let elapsedTime = 0;
let timerInterval = null;
let laps = [];

function formatTime(ms) {
    const totalSeconds = Math.floor(ms / 1000);
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;
    const milliseconds = Math.floor((ms % 1000) / 10);

    const pad = (n) => String(n).padStart(2, '0');
    return {
        main: `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`,
        ms: `.${pad(milliseconds)}`,
        raw: ms
    };
}

function updateDisplay() {
    const formatted = formatTime(elapsedTime);
    document.getElementById('timeMain').innerText = formatted.main;
    document.getElementById('timeMs').innerText = formatted.ms;
}

function startTimer() {
    startTime = Date.now() - elapsedTime;
    timerInterval = setInterval(() => {
        elapsedTime = Date.now() - startTime;
        updateDisplay();
    }, 10);

    document.getElementById('btnStart').style.display = 'none';
    document.getElementById('btnPause').style.display = 'flex';
    document.getElementById('btnLap').disabled = false;
}

function pauseTimer() {
    clearInterval(timerInterval);
    timerInterval = null;

    document.getElementById('btnStart').style.display = 'flex';
    document.getElementById('btnPause').style.display = 'none';
}

function resetTimer() {
    clearInterval(timerInterval);
    timerInterval = null;
    elapsedTime = 0;
    updateDisplay();

    document.getElementById('btnStart').style.display = 'flex';
    document.getElementById('btnPause').style.display = 'none';
    document.getElementById('btnLap').disabled = true;
}

function recordLap() {
    if (elapsedTime === 0) return;
    const previousLapTime = laps.length > 0 ? laps[0].totalMs : 0;
    const lapDiff = elapsedTime - previousLapTime;

    const lap = {
        number: laps.length + 1,
        totalTime: formatTime(elapsedTime),
        lapTime: formatTime(lapDiff),
        totalMs: elapsedTime,
        diffMs: lapDiff
    };

    laps.unshift(lap); // Most recent on top
    renderLaps();
}

function renderLaps() {
    const card = document.getElementById('lapsCard');
    const list = document.getElementById('lapsList');

    if (laps.length === 0) {
        card.style.display = 'none';
        return;
    }

    card.style.display = 'block';

    let fastest = laps[0].diffMs;
    let slowest = laps[0].diffMs;

    if (laps.length > 1) {
        fastest = Math.min(...laps.map(l => l.diffMs));
        slowest = Math.max(...laps.map(l => l.diffMs));
    }

    list.innerHTML = laps.map(l => {
        let highlightClass = '';
        if (laps.length > 1) {
            if (l.diffMs === fastest) highlightClass = 'lap-fastest';
            else if (l.diffMs === slowest) highlightClass = 'lap-slowest';
        }
        return `
            <div class="lap-item ${highlightClass}">
                <span>Vuelta ${String(l.number).padStart(2, '0')}</span>
                <span>+${l.lapTime.main}${l.lapTime.ms}</span>
                <span>${l.totalTime.main}${l.totalTime.ms}</span>
            </div>
        `;
    }).join('');
}

function clearLaps() {
    laps = [];
    renderLaps();
}
</script>
@endpush