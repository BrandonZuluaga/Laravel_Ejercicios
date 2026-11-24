@extends('layouts.app')
@section('title', 'Plataforma de Encuestas')
@section('page-title', '📋 Plataforma de Encuestas en Línea')

@push('styles')
<style>
.surveys-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}
.survey-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 22px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: var(--transition);
}
.survey-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    border-color: rgba(255,255,255,0.15);
}
.q-builder-item {
    background: var(--bg-hover);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 16px;
    margin-bottom: 14px;
}
</style>
@endpush

@section('content')
<div class="flex-between mb-4">
    <div>
        <h2 style="font-size:18px; font-weight:700; margin:0;">Encuestas Disponibles</h2>
        <span style="font-size:12px; color:var(--text-muted);">Crea encuestas interactivas o participa en las existentes</span>
    </div>
    <button type="button" class="btn btn-primary" onclick="toggleSurveyForm()">
        <i class="fa fa-plus"></i> Crear Nueva Encuesta
    </button>
</div>

<!-- CREATE SURVEY MODAL / BOX -->
<div id="surveyFormBox" class="card mb-4" style="display: {{ $errors->any() ? 'block' : 'none' }}; border: 1px solid var(--accent);">
    <div class="card-title">📝 Diseñar Encuesta</div>
    <form action="{{ route('surveys.store') }}" method="POST" id="newSurveyForm">
        @csrf
        <div class="form-group">
            <label class="form-label">Título de la Encuesta *</label>
            <input type="text" name="title" class="form-control" placeholder="Ej: Encuesta de Satisfacción del Cliente" required value="{{ old('title') }}">
        </div>

        <div class="form-group">
            <label class="form-label">Descripción (Opcional)</label>
            <textarea name="description" class="form-control" placeholder="Breve introducción o instrucciones...">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <div class="flex-between mb-2">
                <label class="form-label" style="margin:0;">Preguntas de la Encuesta *</label>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addQuestion()">
                    <i class="fa fa-plus"></i> Agregar Pregunta
                </button>
            </div>

            <div id="questionsContainer">
                <!-- Question 1 Default -->
                <div class="q-builder-item" id="q-block-0">
                    <div class="form-group">
                        <label class="form-label">Pregunta 1</label>
                        <input type="text" name="questions[0][text]" class="form-control" placeholder="¿Cuál es tu opinión sobre...?" required>
                    </div>
                    <label class="form-label">Opciones de Respuesta</label>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                        <input type="text" name="questions[0][options][]" class="form-control" placeholder="Opción A" required>
                        <input type="text" name="questions[0][options][]" class="form-control" placeholder="Opción B" required>
                        <input type="text" name="questions[0][options][]" class="form-control" placeholder="Opción C (opcional)">
                        <input type="text" name="questions[0][options][]" class="form-control" placeholder="Opción D (opcional)">
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Publicar Encuesta</button>
            <button type="button" class="btn btn-secondary" onclick="toggleSurveyForm()">Cancelar</button>
        </div>
    </form>
</div>

<!-- LIST OF SURVEYS -->
@if($surveys->isEmpty())
<div class="card" style="text-align:center; padding:50px 20px; color:var(--text-muted);">
    <div style="font-size:48px; margin-bottom:12px;">📊</div>
    <div>No hay encuestas creadas aún. ¡Crea la primera ahora!</div>
</div>
@else
<div class="surveys-grid">
    @foreach($surveys as $survey)
    <div class="survey-card">
        <div>
            <div class="flex-between mb-2">
                <span class="badge" style="background:rgba(99,102,241,0.15); color:var(--accent-light);">
                    <i class="fa fa-users"></i> {{ $survey->responses_count }} respuestas
                </span>
                <span style="font-size:11px; color:var(--text-muted);">{{ $survey->created_at->diffForHumans() }}</span>
            </div>
            <h3 style="font-size:17px; font-weight:700; color:var(--text-primary); margin-bottom:8px;">{{ $survey->title }}</h3>
            @if($survey->description)
            <p style="font-size:13px; color:var(--text-secondary); line-height:1.4; margin-bottom:14px;">{{ Str::limit($survey->description, 100) }}</p>
            @endif
        </div>

        <div style="border-top:1px solid var(--border); padding-top:14px; margin-top:14px; display:flex; justify-content:space-between; align-items:center;">
            <div style="display:flex; gap:8px;">
                <a href="{{ route('surveys.show', $survey) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-pencil-alt"></i> Responder
                </a>
                <a href="{{ route('surveys.results', $survey) }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-chart-bar"></i> Resultados
                </a>
            </div>
            <form action="{{ route('surveys.destroy', $survey) }}" method="POST" onsubmit="return confirm('¿Eliminar esta encuesta?')">
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
let qCounter = 1;

function toggleSurveyForm() {
    const box = document.getElementById('surveyFormBox');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

function addQuestion() {
    const container = document.getElementById('questionsContainer');
    const div = document.createElement('div');
    div.classList.add('q-builder-item');
    div.id = `q-block-${qCounter}`;
    div.innerHTML = `
        <div class="flex-between mb-2">
            <label class="form-label" style="margin:0;">Pregunta ${qCounter + 1}</label>
            <button type="button" class="btn btn-danger btn-sm" onclick="document.getElementById('q-block-${qCounter}').remove()">Eliminar</button>
        </div>
        <div class="form-group">
            <input type="text" name="questions[${qCounter}][text]" class="form-control" placeholder="Escribe tu pregunta..." required>
        </div>
        <label class="form-label">Opciones de Respuesta</label>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
            <input type="text" name="questions[${qCounter}][options][]" class="form-control" placeholder="Opción A" required>
            <input type="text" name="questions[${qCounter}][options][]" class="form-control" placeholder="Opción B" required>
            <input type="text" name="questions[${qCounter}][options][]" class="form-control" placeholder="Opción C (opcional)">
            <input type="text" name="questions[${qCounter}][options][]" class="form-control" placeholder="Opción D (opcional)">
        </div>
    `;
    container.appendChild(div);
    qCounter++;
}
</script>
@endpush