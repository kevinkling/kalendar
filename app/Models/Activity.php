<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    // Define el nombre de la tabla (en caso de que sea diferente al plural de la clase)
    protected $table = 'activities';

    // Define los campos que pueden ser asignados masivamente
    protected $fillable = [
        'id',
        'activity',
        'subject',
        'start_date',
        'end_date',
        'notes',
    ];

    // Reglas de validación
    public static $rules = [
        'activity'   => 'required|string|max:100',
        'subject'    => 'required|string|max:100',
        'start_date' => 'required|date',
        'end_date'   => 'required|date',
        'notes'      => 'nullable|string|max:1000',
    ];
}
