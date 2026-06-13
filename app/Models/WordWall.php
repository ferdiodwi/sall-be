<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordWall extends Model
{

    protected $table = 'word_walls';

    protected $fillable = [
        'id',
        'user_id',
        'word',
        'meaning',
        'example',
        'image_url',
        'status',
        'review_history',
    ];

    protected $casts = [
        'review_history' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
