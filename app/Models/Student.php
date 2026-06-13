<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'xp',
        'streak',
        'last_active',
        'level',
        'placement_score',
        'placement_date',
        'modules_completed',
        'vocab_mastered',
        'badges',
    ];

    protected $casts = [
        'modules_completed' => 'json',
        'badges' => 'json',
        'last_active' => 'datetime',
        'placement_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
