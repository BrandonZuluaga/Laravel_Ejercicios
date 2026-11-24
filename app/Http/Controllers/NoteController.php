<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('search', '');
        $category = $request->get('category', '');

        $notes = Note::query()
            ->when($search,   fn($q) => $q->where('title', 'like', "%$search%")->orWhere('content', 'like', "%$search%"))
            ->when($category, fn($q) => $q->where('category', $category))
            ->latest()
            ->get();

        $categories = Note::distinct()->pluck('category')->filter()->values();
        return view('notes.index', compact('notes', 'search', 'category', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'category' => 'required|string|max:100',
            'color'    => 'required|string',
        ]);
        Note::create($request->only('title', 'content', 'category', 'color'));
        return back()->with('success', '¡Nota guardada exitosamente!');
    }

    public function update(Request $request, Note $note)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $note->update($request->only('title', 'content', 'category', 'color'));
        return back()->with('success', '¡Nota actualizada!');
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return back()->with('success', '¡Nota eliminada!');
    }
}
