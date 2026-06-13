<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{

    const UPDATED_AT = null;

    protected $fillable = [
        'id',
        'user_id',
        'learned',
        'difficult',
        'goal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
