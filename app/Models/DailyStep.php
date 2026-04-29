<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyStep extends Model
{
    protected $fillable = ['user_id', 'count', 'goal', 'date'];
}
