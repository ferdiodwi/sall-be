<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'question_id',
        'answer_index',
        'explanation_correct',
        'explanation_wrong',
        'related_vocab',
        'review_activity',
    ];

    protected $casts = [
        'related_vocab' => 'json',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
