@extends('layouts.app')
@section('title', 'Responder: ' . $survey->title)
@section('page-title', '📋 Responder Encuesta')

@push('styles')
<style>
.q-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 20px;
}
.radio-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--bg-hover);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    margin-bottom: 10px;
    cursor: pointer;
    transition: var(--transition);
}
.radio-option:hover {
    border-color: var(--accent);
    background: rgba(99,102,241,0.08);
}
.radio-option input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: var(--accent);
}
</style>
@endpush

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <div class="mb-3">
        <a href="{{ route('surveys.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Volver a Encuestas
        </a>
    </div>

    <div class="card mb-4" style="background: linear-gradient(135deg, rgba(99,102,241,0.12) 0%, rgba(168,85,247,0.1) 100%);">
        <h1 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin-bottom: 8px;">{{ $survey->title }}</h1>
        @if($survey->description)
        <p style="color: var(--text-secondary); font-size: 14px; margin: 0;">{{ $survey->description }}</p>
        @endif
    </div>

    <form action="{{ route('surveys.respond', $survey) }}" method="POST">
        @csrf
        @foreach($survey->questions as $i => $question)
        <div class="q-card">
            <div style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 14px;">
                {{ $i + 1 }}. {{ $question->question_text }}
            </div>

            @foreach($question->options as $opt)
            <label class="radio-option">
                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $opt }}" required>
                <span style="font-size: 14px; color: var(--text-primary);">{{ $opt }}</span>
            </label>
            @endforeach
        </div>
        @endforeach

        <button type="submit" class="btn btn-primary w-full" style="padding: 14px;">
            <i class="fa fa-paper-plane"></i> Enviar Respuestas
        </button>
    </form>
</div>
@endsection