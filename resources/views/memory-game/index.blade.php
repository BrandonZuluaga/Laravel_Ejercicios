@extends('layouts.app')
@section('title', 'Juego de Memoria')
@section('page-title', '🧠 Juego Clásico de Memoria')

@push('styles')
<style>
.game-stats {
    display: flex;
    justify-content: space-around;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    margin-bottom: 24px;
}
.stat-box {
    text-align: center;
}
.stat-box-val {
    font-size: 24px;
    font-weight: 800;
    color: var(--accent-light);
}
.stat-box-lbl {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
}

/* CARD BOARD */
.memory-board {
    display: grid;
    gap: 12px;
    perspective: 1000px;
    max-width: 650px;
    margin: 0 auto;
}
.memory-card {
    height: 95px;
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    cursor: pointer;
    user-select: none;
}
.memory-card.flipped, .memory-card.matched {
    transform: rotateY(180deg);
}
.card-front, .card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border);
}
.card-front {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    color: var(--accent-light);
    font-size: 24px;
}
.card-back {
    background: var(--bg-hover);
    transform: rotateY(180deg);
    font-size: 36px;
    border-color: var(--accent);
}
.memory-card.matched .card-back {
    background: rgba(16, 185, 129, 0.2);
    border-color: #10b981;
}

.diff-btn {
    padding: 6px 16px;
    border-radius: 50px;
    border: 1px solid var(--border);
    background: var(--bg-card);
    color: var(--text-secondary);
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}
.diff-btn.active, .diff-btn:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
}
</style>
@endpush

@section('content')
<div style="max-width: 720px; margin: 0 auto;">
    <!-- CONTROLS & DIFFICULTY -->
    <div class="flex-between mb-3">
        <div style="display:flex; gap:8px;">
            <button type="button" class="diff-btn active" onclick="setDifficulty('easy', this)">Fácil (6 pares)</button>
            <button type="button" class="diff-btn" onclick="setDifficulty('medium', this)">Medio (8 pares)</button>
            <button type="button" class="diff-btn" onclick="setDifficulty('hard', this)">Difícil (12 pares)</button>
        </div>
        <button type="button" class="btn btn-primary btn-sm" onclick="initGame()">
            <i class="fa fa-redo"></i> Reiniciar Juego
        </button>
    </div>

    <!-- STATS -->
    <div class="game-stats">
        <div class="stat-box">
            <div class="stat-box-val" id="movesCount">0</div>
            <div class="stat-box-lbl">Movimientos</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-val" id="matchesCount">0 / 6</div>
            <div class="stat-box-lbl">Pares Encontrados</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-val" id="timerCount">00:00</div>
            <div class="stat-box-lbl">Tiempo</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-val" id="scoreCount">0</div>
            <div class="stat-box-lbl">Puntaje</div>
        </div>
    </div>

    <!-- BOARD -->
    <div id="memoryBoard" class="memory-board"></div>

    <!-- WIN MODAL -->
    <div id="winModal" style="display:none; text-align:center; padding:30px; background:var(--bg-card); border:2px solid var(--success); border-radius:var(--radius); margin-top:20px;">
        <div style="font-size:48px;">🏆</div>
        <h2 style="font-size:24px; font-weight:800; color:var(--success); margin:10px 0;">¡Felicitaciones! Has Ganado</h2>
        <p style="color:var(--text-secondary);" id="winSummary"></p>
        <button type="button" class="btn btn-primary mt-3" onclick="initGame()">Jugar de Nuevo</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
const emojisPool = ['🚀', '🍕', '🎮', '💎', '🦁', '🌟', '🎸', '⚽', '🎨', '🔥', '🍩', '🦊'];
let currentDifficulty = 'easy';
let pairsCount = 6;
let cards = [];
let flippedCards = [];
let matchedPairs = 0;
let moves = 0;
let timer = null;
let seconds = 0;
let isLocked = false;

function setDifficulty(diff, btn) {
    currentDifficulty = diff;
    document.querySelectorAll('.diff-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    if (diff === 'easy') pairsCount = 6;
    else if (diff === 'medium') pairsCount = 8;
    else pairsCount = 12;
    initGame();
}

function initGame() {
    clearInterval(timer);
    timer = null;
    seconds = 0;
    moves = 0;
    matchedPairs = 0;
    flippedCards = [];
    isLocked = false;

    document.getElementById('movesCount').innerText = '0';
    document.getElementById('matchesCount').innerText = `0 / ${pairsCount}`;
    document.getElementById('timerCount').innerText = '00:00';
    document.getElementById('scoreCount').innerText = '0';
    document.getElementById('winModal').style.display = 'none';

    // Pick random emojis
    const selectedEmojis = emojisPool.slice(0, pairsCount);
    const cardItems = [...selectedEmojis, ...selectedEmojis];
    cardItems.sort(() => Math.random() - 0.5);

    const board = document.getElementById('memoryBoard');
    board.innerHTML = '';

    // Adjust grid columns
    if (pairsCount === 6) board.style.gridTemplateColumns = 'repeat(4, 1fr)';
    else if (pairsCount === 8) board.style.gridTemplateColumns = 'repeat(4, 1fr)';
    else board.style.gridTemplateColumns = 'repeat(6, 1fr)';

    cardItems.forEach((emoji, index) => {
        const card = document.createElement('div');
        card.classList.add('memory-card');
        card.dataset.emoji = emoji;
        card.dataset.index = index;

        card.innerHTML = `
            <div class="card-front"><i class="fa fa-question"></i></div>
            <div class="card-back">${emoji}</div>
        `;

        card.addEventListener('click', () => flipCard(card));
        board.appendChild(card);
    });
}

function startTimer() {
    if (timer) return;
    timer = setInterval(() => {
        seconds++;
        const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
        const secs = String(seconds % 60).padStart(2, '0');
        document.getElementById('timerCount').innerText = `${mins}:${secs}`;
    }, 1000);
}

function flipCard(card) {
    if (isLocked || card.classList.contains('flipped') || card.classList.contains('matched')) return;

    startTimer();
    card.classList.add('flipped');
    flippedCards.push(card);

    if (flippedCards.length === 2) {
        moves++;
        document.getElementById('movesCount').innerText = moves;
        checkMatch();
    }
}

function checkMatch() {
    isLocked = true;
    const [c1, c2] = flippedCards;

    if (c1.dataset.emoji === c2.dataset.emoji) {
        c1.classList.add('matched');
        c2.classList.add('matched');
        matchedPairs++;
        document.getElementById('matchesCount').innerText = `${matchedPairs} / ${pairsCount}`;
        
        // Calculate score
        const currentScore = Math.max(10, Math.round((matchedPairs * 100) - (moves * 5) - (seconds * 2)));
        document.getElementById('scoreCount').innerText = currentScore;

        flippedCards = [];
        isLocked = false;

        if (matchedPairs === pairsCount) {
            handleVictory();
        }
    } else {
        setTimeout(() => {
            c1.classList.remove('flipped');
            c2.classList.remove('flipped');
            flippedCards = [];
            isLocked = false;
        }, 900);
    }
}

function handleVictory() {
    clearInterval(timer);
    const finalScore = Math.max(50, Math.round((pairsCount * 150) - (moves * 10) - (seconds * 3)));
    document.getElementById('scoreCount').innerText = finalScore;
    document.getElementById('winSummary').innerText = `Completaste el juego en ${seconds} segundos y ${moves} movimientos. Tu puntaje final es de ${finalScore} pts.`;
    document.getElementById('winModal').style.display = 'block';
}

document.addEventListener('DOMContentLoaded', initGame);
</script>
@endpush