<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasUuids;

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
