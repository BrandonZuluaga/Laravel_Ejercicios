<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordGeneratorController extends Controller
{
    public function index()
    {
        return view('password-generator.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'length'     => 'required|integer|min:8|max:64',
            'uppercase'  => 'nullable|boolean',
            'lowercase'  => 'nullable|boolean',
            'numbers'    => 'nullable|boolean',
            'symbols'    => 'nullable|boolean',
        ]);

        $length    = (int) $request->length;
        $chars     = '';

        if ($request->boolean('uppercase')) $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($request->boolean('lowercase')) $chars .= 'abcdefghijklmnopqrstuvwxyz';
        if ($request->boolean('numbers'))   $chars .= '0123456789';
        if ($request->boolean('symbols'))   $chars .= '!@#$%^&*()_+-=[]{}|;:,.<>?';

        if (empty($chars)) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }

        $password = '';
        $charsLen = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $charsLen - 1)];
        }

        // Calculate strength
        $strength = 0;
        if ($length >= 12) $strength++;
        if ($length >= 16) $strength++;
        if ($request->boolean('uppercase'))  $strength++;
        if ($request->boolean('lowercase'))  $strength++;
        if ($request->boolean('numbers'))    $strength++;
        if ($request->boolean('symbols'))    $strength++;

        $strengthLabel = match(true) {
            $strength <= 2 => ['label' => 'Débil',    'color' => '#ef4444'],
            $strength <= 4 => ['label' => 'Media',    'color' => '#f59e0b'],
            default        => ['label' => 'Fuerte',   'color' => '#10b981'],
        };

        return view('password-generator.index', compact('password', 'length', 'strength', 'strengthLabel') + $request->only('uppercase', 'lowercase', 'numbers', 'symbols'));
    }
}
