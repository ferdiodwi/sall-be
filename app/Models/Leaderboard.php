<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasUuids;

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
