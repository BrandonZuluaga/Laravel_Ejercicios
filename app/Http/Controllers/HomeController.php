<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $apps = [
            ['icon' => '✅', 'title' => 'Lista de Tareas', 'desc' => 'Gestiona tus tareas pendientes', 'route' => 'tasks.index', 'color' => '#6366f1'],
            ['icon' => '💰', 'title' => 'Calculadora de Propinas', 'desc' => 'Calcula propinas fácilmente', 'route' => 'tip.index', 'color' => '#10b981'],
            ['icon' => '🔐', 'title' => 'Generador de Contraseñas', 'desc' => 'Contraseñas seguras al instante', 'route' => 'password.index', 'color' => '#f59e0b'],
            ['icon' => '💳', 'title' => 'Gestor de Gastos', 'desc' => 'Controla tus finanzas', 'route' => 'expenses.index', 'color' => '#ef4444'],
            ['icon' => '📅', 'title' => 'Sistema de Reservas', 'desc' => 'Reserva citas y servicios', 'route' => 'reservations.index', 'color' => '#8b5cf6'],
            ['icon' => '📝', 'title' => 'Gestor de Notas', 'desc' => 'Organiza tus ideas', 'route' => 'notes.index', 'color' => '#06b6d4'],
            ['icon' => '🗓️', 'title' => 'Calendario de Eventos', 'desc' => 'Planifica tus eventos', 'route' => 'calendar.index', 'color' => '#ec4899'],
            ['icon' => '🍳', 'title' => 'Plataforma de Recetas', 'desc' => 'Descubre y comparte recetas', 'route' => 'recipes.index', 'color' => '#f97316'],
            ['icon' => '🃏', 'title' => 'Juego de Memoria', 'desc' => 'Pon a prueba tu memoria', 'route' => 'memory.index', 'color' => '#14b8a6'],
            ['icon' => '📊', 'title' => 'Plataforma de Encuestas', 'desc' => 'Crea y analiza encuestas', 'route' => 'surveys.index', 'color' => '#a855f7'],
            ['icon' => '⏱️', 'title' => 'Cronómetro Online', 'desc' => 'Mide el tiempo con precisión', 'route' => 'stopwatch.index', 'color' => '#3b82f6'],
        ];
        return view('home.index', compact('apps'));
    }
}
