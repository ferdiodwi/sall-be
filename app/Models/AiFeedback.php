<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiFeedback extends Model
{

    protected $table = 'ai_feedback';

    protected $fillable = [
        'id',
        'user_id',
        'weak_topic',
        'message',
        'recommended_activity',
        'est_time_minutes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
