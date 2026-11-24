# 🚀 Laravel 13 MVC - Colección de 11 Aplicaciones Web

Proyecto integral desarrollado en **PHP 8.4** con el framework **Laravel 13** y base de datos **MySQL**, implementando la arquitectura **Modelo-Vista-Controlador (MVC)**. Cada aplicación cuenta con su propia vista dedicada, controlador y modelo de persistencia (cuando aplica), encapsulado en una interfaz moderna con tema oscuro, navegación lateral y diseño responsivo.

---

## 📋 Catálogo de Ejercicios Implementados

| # | Módulo / Ejercicio | Ruta URL | Descripción |
|---|---|---|---|
| **0** | **Dashboard Principal** | `/` | Panel general con accesos directos interactivos a todas las aplicaciones. |
| **1** | **Lista de Tareas** | `/tasks` | Gestión de tareas con filtros (todas, pendientes, completadas), creación, marcado y eliminación. |
| **2** | **Calculadora de Propinas** | `/tip-calculator` | Cálculo automático en **Pesos Colombianos (COP)** con porcentajes predefinidos y división equitativa por personas. |
| **3** | **Generador de Contraseñas** | `/password-generator` | Generador de contraseñas seguras con longitud configurable, tipos de caracteres, medidor de seguridad y copiado al portapapeles. |
| **4** | **Gestor de Gastos** | `/expenses` | Registro diario categorizado en **COP**, resumen mensual y gráficos de desglose por categoría. |
| **5** | **Sistema de Reservas** | `/reservations` | Agenda de citas y servicios con visualización de disponibilidad de horarios en tiempo real. |
| **6** | **Gestor de Notas** | `/notes` | Bloc de notas tipo tarjetas con categorías, buscador dinámico y selector de colores. |
| **7** | **Calendario de Eventos** | `/calendar` | Calendario mensual interactivo con navegación entre meses y gestión de eventos con etiquetas de color. |
| **8** | **Plataforma de Recetas** | `/recipes` | Catálogo de recetas culinarias con ingredientes, pasos de preparación, porciones y dificultad. |
| **9** | **Juego de Memoria** | `/memory-game` | Juego clásico de parejas con cartas 3D giratorias, 3 niveles de dificultad, cronómetro y cálculo de puntaje. |
| **10** | **Plataforma de Encuestas** | `/surveys` | Creación de encuestas dinámicas, votación en línea y visualización de resultados con barras porcentuales. |
| **11** | **Cronómetro Online** | `/stopwatch` | Cronómetro digital de alta precisión con inicio, pausa, reinicio y registro de vueltas / tiempos parciales. |

---

## 🛠️ Tecnologías Utilizadas

- **Lenguaje:** PHP 8.4+
- **Framework Backend:** Laravel 13
- **Base de Datos:** MySQL / MariaDB (vía WAMP / XAMPP)
- **Frontend:** Blade Templates, CSS3 personalizado (Variables, Glassmorphism, Flexbox, CSS Grid), FontAwesome 6, JavaScript moderno (ES6+)
- **Moneda oficial:** Pesos Colombianos (COP)

---

## ⚙️ Requisitos Previos

- [PHP >= 8.2](https://www.php.net/) (con extensiones `pdo_mysql`, `mbstring`, `openssl`, `curl`)
- [Composer](https://getcomposer.org/)
- Servidor MySQL activo (por ejemplo, mediante **WAMP Server** en el puerto 3306)
- [Git](https://git-scm.com/)

---

## 🚀 Instalación y Puesta en Marcha

1. **Clonar el repositorio:**
   ```bash
   git clone <URL_DE_TU_REPOSITORIO>
   cd laravel-mvc-exercises
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Configurar el archivo de entorno `.env`:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Verificar las credenciales de la base de datos en `.env`:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_mvc_exercises
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Crear la base de datos y ejecutar las migraciones con datos de prueba:**
   ```bash
   php artisan migrate --seed
   ```

6. **Iniciar el servidor local de desarrollo:**
   ```bash
   php artisan serve
   ```
   Abre tu navegador en: **`http://127.0.0.1:8000`**

---

## 📂 Estructura del Proyecto (MVC)

```
laravel-mvc-exercises/
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php
│   │   ├── TaskController.php
│   │   ├── TipCalculatorController.php
│   │   ├── PasswordGeneratorController.php
│   │   ├── ExpenseController.php
│   │   ├── ReservationController.php
│   │   ├── NoteController.php
│   │   ├── CalendarController.php
│   │   ├── RecipeController.php
│   │   ├── MemoryGameController.php
│   │   ├── SurveyController.php
│   │   └── StopwatchController.php
│   └── Models/
│       ├── Task.php
│       ├── Expense.php
│       ├── Reservation.php
│       ├── Note.php
│       ├── Event.php
│       ├── Recipe.php
│       ├── Survey.php
│       ├── Question.php
│       └── Response.php
├── database/
│   ├── migrations/
│   └── seeders/DatabaseSeeder.php
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── home/index.blade.php
│   ├── tasks/
│   ├── tip-calculator/
│   ├── password-generator/
│   ├── expenses/
│   ├── reservations/
│   ├── notes/
│   ├── calendar/
│   ├── recipes/
│   ├── memory-game/
│   ├── surveys/
│   └── stopwatch/
└── routes/web.php
```

---

## 📄 Licencia

Este proyecto es de código abierto bajo la licencia [MIT](https://opensource.org/licenses/MIT).
