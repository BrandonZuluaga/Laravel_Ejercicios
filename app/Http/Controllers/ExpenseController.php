<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $expenses = Expense::whereYear('date', $year)
            ->whereMonth('date', $mon)
            ->latest('date')
            ->get();

        $summary = Expense::whereYear('date', $year)
            ->whereMonth('date', $mon)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $totalMonth = $expenses->sum('amount');

        $categories = ['Alimentación', 'Transporte', 'Entretenimiento', 'Salud', 'Educación', 'Ropa', 'Hogar', 'Otros'];

        return view('expenses.index', compact('expenses', 'summary', 'totalMonth', 'month', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount'      => 'required|numeric|min:0.01',
            'category'    => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'date'        => 'required|date',
        ]);
        Expense::create($request->only('amount', 'category', 'description', 'date'));
        return back()->with('success', '¡Gasto registrado exitosamente!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', '¡Gasto eliminado!');
    }
}
