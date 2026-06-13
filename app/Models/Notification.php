<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'body',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
