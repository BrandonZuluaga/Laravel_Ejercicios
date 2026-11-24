<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['amount', 'category', 'description', 'date'];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];
}
