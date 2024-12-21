<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'team_a',
        'team_b',
        'winner',
        'score_a',
        'score_b',
        'round',
    ];

    protected $casts = [
        'team_a' => 'array', 
        'team_b' => 'array', 
    ];
}
