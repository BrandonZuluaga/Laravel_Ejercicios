<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $reservations = Reservation::whereDate('date', $date)->orderBy('time')->get();
        $allReservations = Reservation::where('date', '>=', now()->toDateString())->orderBy('date')->orderBy('time')->get();

        $services = ['Consulta General', 'Corte de Cabello', 'Masaje Relajante', 'Clase de Yoga', 'Sesión de Fotografía', 'Asesoría Personal'];
        $bookedTimes = $reservations->pluck('time')->map(fn($t) => substr($t, 0, 5))->toArray();

        $allTimes = [];
        for ($h = 8; $h < 20; $h++) {
            $allTimes[] = sprintf('%02d:00', $h);
            $allTimes[] = sprintf('%02d:30', $h);
        }

        return view('reservations.index', compact('reservations', 'allReservations', 'date', 'services', 'bookedTimes', 'allTimes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'service' => 'required|string',
            'date'    => 'required|date|after_or_equal:today',
            'time'    => 'required',
            'notes'   => 'nullable|string|max:500',
        ]);

        $exists = Reservation::whereDate('date', $request->date)
            ->where('time', $request->time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($exists) {
            return back()->withErrors(['time' => 'Este horario ya está reservado. Por favor elige otro.'])->withInput();
        }

        Reservation::create($request->only('name', 'email', 'service', 'date', 'time', 'notes'));
        return back()->with('success', '¡Reserva confirmada exitosamente!');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->update(['status' => 'cancelled']);
        return back()->with('success', '¡Reserva cancelada!');
    }
}
