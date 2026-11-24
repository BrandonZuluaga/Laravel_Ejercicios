<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['name', 'email', 'service', 'date', 'time', 'status', 'notes'];

    protected $casts = [
        'date' => 'date',
    ];
}
