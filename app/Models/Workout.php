<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = ['user_id', 'type', 'duration', 'date'];

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
