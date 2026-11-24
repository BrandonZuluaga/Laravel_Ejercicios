<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $tasks = match($filter) {
            'pending'   => Task::where('completed', false)->latest()->get(),
            'completed' => Task::where('completed', true)->latest()->get(),
            default     => Task::latest()->get(),
        };
        return view('tasks.index', compact('tasks', 'filter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
        Task::create($request->only('title', 'description'));
        return back()->with('success', '¡Tarea agregada exitosamente!');
    }

    public function toggle(Task $task)
    {
        $task->update(['completed' => !$task->completed]);
        return back()->with('success', $task->completed ? '¡Tarea completada!' : 'Tarea marcada como pendiente.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success', '¡Tarea eliminada exitosamente!');
    }
}
