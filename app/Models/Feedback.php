<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasUuids;

    protected $table = 'feedback';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'question_id',
        'correct',
        'shown_at',
    ];

    protected $casts = [
        'correct' => 'boolean',
        'shown_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
