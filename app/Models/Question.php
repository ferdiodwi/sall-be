<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    protected $fillable = [
        'id',
        'quiz_id',
        'type',
        'prompt',
        'passage',
        'options',
        'topic',
        'order',
    ];

    protected $casts = [
        'options' => 'json',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answer()
    {
        return $this->hasOne(Answer::class);
    }
}
