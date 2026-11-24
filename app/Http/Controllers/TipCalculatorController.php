<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipCalculatorController extends Controller
{
    public function index()
    {
        return view('tip-calculator.index');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'amount'  => 'required|numeric|min:1',
            'tip'     => 'required|numeric|min:0|max:100',
            'people'  => 'required|integer|min:1|max:100',
        ]);

        $amount     = (float) $request->amount;
        $tipPercent = (float) $request->tip;
        $people     = (int)   $request->people;

        $tipAmount    = $amount * ($tipPercent / 100);
        $totalAmount  = $amount + $tipAmount;
        $perPerson    = $totalAmount / $people;
        $tipPerPerson = $tipAmount / $people;

        return view('tip-calculator.index', compact(
            'amount', 'tipPercent', 'people',
            'tipAmount', 'totalAmount', 'perPerson', 'tipPerPerson'
        ));
    }
}
