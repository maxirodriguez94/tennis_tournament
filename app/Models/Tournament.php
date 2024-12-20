<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = ['name', 'is_doubles', 'gender'];

    
    public function matches()
    {
        return $this->hasMany(Match::class);
    }
}

