@extends('layouts.app')
@section('title', 'Resultados: ' . $survey->title)
@section('page-title', '📊 Resultados de la Encuesta')

@push('styles')
<style>
.result-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 22px;
    margin-bottom: 20px;
}
.option-bar-container {
    margin-bottom: 14px;
}
.option-label-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    color: var(--text-primary);
}
.option-bar {
    height: 10px;
    background: var(--border);
    border-radius: 5px;
    overflow: hidden;
}
.option-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--accent), var(--accent-light));
    border-radius: 5px;
    transition: width 0.6s ease;
}
</style>
@endpush

@section('content')
<div style="max-width: 720px; margin: 0 auto;">
    <div class="mb-3 flex-between">
        <a href="{{ route('surveys.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Volver a Encuestas
        </a>
        <a href="{{ route('surveys.show', $survey) }}" class="btn btn-primary btn-sm">
            <i class="fa fa-pencil-alt"></i> Responder Encuesta
        </a>
    </div>

    <div class="card mb-4">
        <h1 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin-bottom: 6px;">{{ $survey->title }}</h1>
        @if($survey->description)
        <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 12px;">{{ $survey->description }}</p>
        @endif
        <div style="font-size: 12px; color: var(--text-muted);">
            <i class="fa fa-info-circle"></i> Resultados en tiempo real basados en las respuestas registradas en la base de datos.
        </div>
    </div>

    @foreach($survey->questions as $i => $question)
    @php
        $totalVotes = $question->responses->count();
        $optionCounts = $question->responses->groupBy('selected_option')->map->count();
    @endphp
    <div class="result-card">
        <div class="flex-between mb-3">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0;">
                {{ $i + 1 }}. {{ $question->question_text }}
            </h3>
            <span class="badge" style="background: rgba(99,102,241,0.15); color: var(--accent-light);">
                {{ $totalVotes }} votos
            </span>
        </div>

        @foreach($question->options as $opt)
        @php
            $count = $optionCounts[$opt] ?? 0;
            $percentage = $totalVotes > 0 ? round(($count / $totalVotes) * 100, 1) : 0;
        @endphp
        <div class="option-bar-container">
            <div class="option-label-row">
                <span>{{ $opt }}</span>
                <span>{{ $count }} votos ({{ $percentage }}%)</span>
            </div>
            <div class="option-bar">
                <div class="option-bar-fill" style="width: {{ $percentage }}%;"></div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
</div>
@endsection