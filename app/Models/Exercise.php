<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = ['workout_id', 'name', 'sets', 'reps'];

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
}
