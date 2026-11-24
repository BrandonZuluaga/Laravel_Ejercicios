<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::withCount('responses')->latest()->get();
        return view('surveys.index', compact('surveys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions'   => 'required|array|min:1',
            'questions.*.text'    => 'required|string',
            'questions.*.options' => 'required|array|min:2',
        ]);

        $survey = Survey::create($request->only('title', 'description'));

        foreach ($request->questions as $i => $q) {
            $survey->questions()->create([
                'question_text' => $q['text'],
                'options'       => array_values(array_filter($q['options'])),
                'order'         => $i,
            ]);
        }

        return back()->with('success', '¡Encuesta creada exitosamente!');
    }

    public function show(Survey $survey)
    {
        $survey->load('questions');
        return view('surveys.show', compact('survey'));
    }

    public function respond(Request $request, Survey $survey)
    {
        $survey->load('questions');

        foreach ($survey->questions as $question) {
            $request->validate([
                "answers.{$question->id}" => 'required|string',
            ]);
        }

        $sessionId = session()->getId();

        foreach ($survey->questions as $question) {
            Response::create([
                'survey_id'          => $survey->id,
                'question_id'        => $question->id,
                'selected_option'    => $request->answers[$question->id],
                'respondent_session' => $sessionId,
            ]);
        }

        return redirect()->route('surveys.results', $survey)->with('success', '¡Gracias por tu respuesta!');
    }

    public function results(Survey $survey)
    {
        $survey->load(['questions.responses']);
        return view('surveys.results', compact('survey'));
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();
        return back()->with('success', '¡Encuesta eliminada!');
    }
}
