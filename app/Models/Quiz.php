<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{

    protected $table = 'quizzes';

    protected $fillable = [
        'id',
        'module_id',
        'level',
        'title',
        'activity_type',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
