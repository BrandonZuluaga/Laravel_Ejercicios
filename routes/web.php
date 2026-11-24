<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TipCalculatorController;
use App\Http\Controllers\PasswordGeneratorController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\MemoryGameController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\StopwatchController;

// Home - Dashboard
Route::get('/', [HomeController::class, 'index'])->name('home');

// 1. Lista de Tareas
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

// 2. Calculadora de Propinas
Route::get('/tip-calculator', [TipCalculatorController::class, 'index'])->name('tip.index');
Route::post('/tip-calculator', [TipCalculatorController::class, 'calculate'])->name('tip.calculate');

// 3. Generador de Contraseñas
Route::get('/password-generator', [PasswordGeneratorController::class, 'index'])->name('password.index');
Route::post('/password-generator', [PasswordGeneratorController::class, 'generate'])->name('password.generate');

// 4. Gestor de Gastos
Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

// 5. Sistema de Reservas
Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

// 6. Gestor de Notas
Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
Route::patch('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

// 7. Calendario de Eventos
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');
Route::patch('/calendar/{event}', [CalendarController::class, 'update'])->name('calendar.update');
Route::delete('/calendar/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');

// 8. Plataforma de Recetas
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

// 9. Juego de Memoria
Route::get('/memory-game', [MemoryGameController::class, 'index'])->name('memory.index');

// 10. Plataforma de Encuestas
Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
Route::get('/surveys/{survey}', [SurveyController::class, 'show'])->name('surveys.show');
Route::post('/surveys/{survey}/respond', [SurveyController::class, 'respond'])->name('surveys.respond');
Route::get('/surveys/{survey}/results', [SurveyController::class, 'results'])->name('surveys.results');
Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');

// 11. Cronómetro Online
Route::get('/stopwatch', [StopwatchController::class, 'index'])->name('stopwatch.index');
