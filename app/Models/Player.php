<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'skill_level',
        'gender',
        'strength',
        'speed',
        'reaction_time',
    ];

    protected $attributes = [
        'strength' => 0,
        'speed' => 0,
        'reaction_time' => 0,
    ];
}
