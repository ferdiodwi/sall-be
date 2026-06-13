<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{

    protected $fillable = [
        'id',
        'class_id',
        'user_id',
        'xp',
        'week_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
