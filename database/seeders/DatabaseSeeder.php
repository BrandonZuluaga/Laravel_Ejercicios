<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Expense;
use App\Models\Reservation;
use App\Models\Note;
use App\Models\Event;
use App\Models\Recipe;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Response;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tasks
        Task::create(['title' => 'Completar proyecto de Laravel MVC', 'description' => 'Revisar todas las vistas y controladores de los 11 ejercicios.', 'completed' => true]);
        Task::create(['title' => 'Repasar conceptos de arquitectura MVC', 'description' => 'Modelos, Vistas, Controladores y Enrutamiento en Laravel.', 'completed' => false]);
        Task::create(['title' => 'Subir cambios al repositorio de Git', 'description' => 'Realizar commit descriptivo con todos los ejercicios.', 'completed' => false]);
        Task::create(['title' => 'Diseñar presentación del proyecto', 'description' => 'Preparar diapositivas y demostración en vivo.', 'completed' => false]);

        // 4. Expenses
        Expense::create(['amount' => 180000, 'category' => 'Alimentación', 'description' => 'Supermercado quincenal', 'date' => now()->toDateString()]);
        Expense::create(['amount' => 45000, 'category' => 'Transporte', 'description' => 'Gasolina / Transporte público', 'date' => now()->subDays(2)->toDateString()]);
        Expense::create(['amount' => 38000, 'category' => 'Entretenimiento', 'description' => 'Suscripción streaming y cine', 'date' => now()->subDays(5)->toDateString()]);
        Expense::create(['amount' => 28500, 'category' => 'Salud', 'description' => 'Medicamentos en droguería', 'date' => now()->subDays(7)->toDateString()]);
        Expense::create(['amount' => 120000, 'category' => 'Educación', 'description' => 'Curso técnico de programación', 'date' => now()->subDays(10)->toDateString()]);

        // 5. Reservations
        Reservation::create(['name' => 'Ana Martínez', 'email' => 'ana@example.com', 'service' => 'Consulta General', 'date' => now()->toDateString(), 'time' => '09:00', 'notes' => 'Primera visita médica.', 'status' => 'confirmed']);
        Reservation::create(['name' => 'Luis Fernández', 'email' => 'luis@example.com', 'service' => 'Masaje Relajante', 'date' => now()->toDateString(), 'time' => '14:30', 'notes' => 'Sesión de 1 hora.', 'status' => 'confirmed']);
        Reservation::create(['name' => 'María Torres', 'email' => 'maria@example.com', 'service' => 'Clase de Yoga', 'date' => now()->addDays(1)->toDateString(), 'time' => '10:00', 'notes' => null, 'status' => 'confirmed']);

        // 6. Notes
        Note::create(['title' => 'Ideas para el proyecto', 'content' => 'Implementar un panel moderno con tema oscuro, iconos intuitivos y validación en tiempo real.', 'category' => 'Ideas', 'color' => '#6366f1']);
        Note::create(['title' => 'Comandos clave de Laravel', 'content' => "php artisan serve\nphp artisan migrate\nphp artisan route:list", 'category' => 'Trabajo', 'color' => '#10b981']);
        Note::create(['title' => 'Recordatorio de reunión', 'content' => 'Reunión de revisión el viernes a las 3:00 PM vía Zoom.', 'category' => 'Personal', 'color' => '#f59e0b']);

        // 7. Calendar Events
        Event::create(['title' => 'Entrega de Proyecto Laravel', 'start_date' => now()->toDateString(), 'start_time' => '18:00', 'description' => 'Presentación de los 11 ejercicios desarrollados.', 'color' => '#6366f1']);
        Event::create(['title' => 'Reunión de Equipo', 'start_date' => now()->addDays(3)->toDateString(), 'start_time' => '10:00', 'description' => 'Planificación del próximo sprint.', 'color' => '#10b981']);
        Event::create(['title' => 'Taller de Desarrollo Web', 'start_date' => now()->addDays(7)->toDateString(), 'start_time' => '15:30', 'description' => 'Capacitación sobre PHP 8.4 y Laravel.', 'color' => '#f59e0b']);

        // 8. Recipes
        Recipe::create([
            'title' => 'Pasta Carbonara Auténtica',
            'ingredients' => "350g Spaghetti\n150g Guanciale o Panceta\n4 Yemas de huevo frescas\n80g Queso Pecorino Romano\nPimienta negra molida al gusto\nSal al gusto",
            'instructions' => "1. Poner a hervir abundante agua con sal y cocer la pasta al dente.\n2. Dorar el guanciale en una sartén a fuego medio hasta que quede crujiente.\n3. En un bol, batir las yemas con el queso pecorino y abundante pimienta negra.\n4. Escurrir la pasta reservando un poco del agua de cocción.\n5. Mezclar la pasta caliente con el guanciale fuera del fuego y verter la mezcla de huevo, revolviendo rápido para crear una crema sedosa.\n6. Servir de inmediato con más queso y pimienta.",
            'category' => 'Almuerzo',
            'prep_time' => 25,
            'servings' => 4,
            'difficulty' => 'Media'
        ]);

        Recipe::create([
            'title' => 'Guacamole Tradicional',
            'ingredients' => "3 Aguacates maduros\n1 Tomate picado en cubos\n1/2 Cebolla morada finamente picada\n1 Chile jalapeño o serrano\nZumo de 1 limón\nCilantro fresco picado\nSal al gusto",
            'instructions' => "1. Cortar los aguacates, retirar el hueso y machacar la pulpa con un tenedor en un tazón.\n2. Agregar la cebolla picada, el tomate, el chile y el cilantro.\n3. Añadir el zumo de limón y sal al gusto.\n4. Mezclar con suavidad manteniendo cierta textura rústica.\n5. Servir acompañado de totopos o tortillas de maíz.",
            'category' => 'Snack',
            'prep_time' => 15,
            'servings' => 6,
            'difficulty' => 'Fácil'
        ]);

        Recipe::create([
            'title' => 'Tiramisú Casero Clásico',
            'ingredients' => "500g Queso Mascarpone\n4 Huevos\n100g Azúcar\n300g Bizcochos de soletilla\n1 taza Café espresso fuerte\nCacao en polvo amargo\nLicor Amaretto (opcional)",
            'instructions' => "1. Separar las claras de las yemas. Montar las claras a punto de nieve.\n2. Batir las yemas con el azúcar hasta blanquear y añadir el queso mascarpone.\n3. Integrar las claras montadas a la crema con movimientos envolventes.\n4. Remojar brevemente los bizcochos en el café y formar una capa en el fondo del molde.\n5. Cubrir con la mitad de la crema. Repetir con otra capa de bizcochos y crema restante.\n6. Refrigerar mínimo 4 horas y espolvorear con cacao antes de servir.",
            'category' => 'Postre',
            'prep_time' => 40,
            'servings' => 8,
            'difficulty' => 'Media'
        ]);

        // 10. Surveys
        $survey = Survey::create([
            'title' => 'Satisfacción con el Sistema de Aprendizaje',
            'description' => 'Queremos conocer tu opinión sobre las tecnologías y herramientas web utilizadas en los proyectos.'
        ]);

        $q1 = $survey->questions()->create([
            'question_text' => '¿Cuál es tu framework PHP preferido?',
            'options' => ['Laravel', 'Symfony', 'CodeIgniter', 'Slim PHP'],
            'order' => 0
        ]);

        $q2 = $survey->questions()->create([
            'question_text' => '¿Qué base de datos utilizas con mayor frecuencia?',
            'options' => ['MySQL / MariaDB', 'PostgreSQL', 'SQLite', 'MongoDB'],
            'order' => 1
        ]);

        // Sample responses
        Response::create(['survey_id' => $survey->id, 'question_id' => $q1->id, 'selected_option' => 'Laravel', 'respondent_session' => 'demo_1']);
        Response::create(['survey_id' => $survey->id, 'question_id' => $q1->id, 'selected_option' => 'Laravel', 'respondent_session' => 'demo_2']);
        Response::create(['survey_id' => $survey->id, 'question_id' => $q1->id, 'selected_option' => 'Symfony', 'respondent_session' => 'demo_3']);
        Response::create(['survey_id' => $survey->id, 'question_id' => $q2->id, 'selected_option' => 'MySQL / MariaDB', 'respondent_session' => 'demo_1']);
        Response::create(['survey_id' => $survey->id, 'question_id' => $q2->id, 'selected_option' => 'MySQL / MariaDB', 'respondent_session' => 'demo_2']);
        Response::create(['survey_id' => $survey->id, 'question_id' => $q2->id, 'selected_option' => 'PostgreSQL', 'respondent_session' => 'demo_3']);
    }
}
