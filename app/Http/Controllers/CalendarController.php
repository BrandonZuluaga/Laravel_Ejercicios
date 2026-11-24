<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $year  = $request->get('year',  now()->year);
        $month = $request->get('month', now()->month);

        $date       = Carbon::create($year, $month, 1);
        $prevMonth  = $date->copy()->subMonth();
        $nextMonth  = $date->copy()->addMonth();

        $events = Event::whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->orderBy('start_date')
            ->get();

        // Build calendar grid
        $firstDay    = $date->dayOfWeek; // 0=Sunday
        $daysInMonth = $date->daysInMonth;

        return view('calendar.index', compact('events', 'year', 'month', 'date', 'prevMonth', 'nextMonth', 'firstDay', 'daysInMonth'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'description'=> 'nullable|string|max:500',
            'color'      => 'required|string',
        ]);
        Event::create($request->only('title', 'start_date', 'end_date', 'start_time', 'description', 'color'));
        return back()->with('success', '¡Evento creado exitosamente!');
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'start_date' => 'required|date',
            'description'=> 'nullable|string|max:500',
            'color'      => 'required|string',
        ]);
        $event->update($request->only('title', 'start_date', 'end_date', 'start_time', 'description', 'color'));
        return back()->with('success', '¡Evento actualizado!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', '¡Evento eliminado!');
    }
}
