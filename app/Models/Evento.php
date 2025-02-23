<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    static $rules=[
        'title' => 'required',
        'description' => 'required',
        'start' => 'required',
        'end' => 'required'
    ];

    protected $fillable = ['title', 'description', 'start', 'end'];
}
