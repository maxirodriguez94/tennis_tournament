<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model {
    protected $fillable = [ 'name', 'skill_level', 'gender', 'strength', 'speed', 'reaction_time' ];

    public function matches() {
        return $this->belongsToMany( Match::class )->withPivot( 'team' );

    }
}

