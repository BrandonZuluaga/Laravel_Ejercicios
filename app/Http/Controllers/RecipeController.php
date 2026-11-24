<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('search', '');
        $category = $request->get('category', '');

        $recipes = Recipe::query()
            ->when($search,   fn($q) => $q->where('title', 'like', "%$search%")->orWhere('ingredients', 'like', "%$search%"))
            ->when($category, fn($q) => $q->where('category', $category))
            ->latest()
            ->get();

        $categories = ['Desayuno', 'Almuerzo', 'Cena', 'Postre', 'Snack', 'Bebida', 'Vegetariano'];
        return view('recipes.index', compact('recipes', 'search', 'category', 'categories'));
    }

    public function show(Recipe $recipe)
    {
        return view('recipes.show', compact('recipe'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'ingredients'  => 'required|string',
            'instructions' => 'required|string',
            'category'     => 'required|string',
            'prep_time'    => 'required|integer|min:1',
            'servings'     => 'required|integer|min:1',
            'difficulty'   => 'required|in:Fácil,Media,Difícil',
        ]);
        Recipe::create($request->only('title', 'ingredients', 'instructions', 'category', 'prep_time', 'servings', 'difficulty'));
        return back()->with('success', '¡Receta publicada exitosamente!');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return back()->with('success', '¡Receta eliminada!');
    }
}
