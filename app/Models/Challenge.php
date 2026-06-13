<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasUuids;

    const UPDATED_AT = null;

    protected $fillable = [
        'id',
        'title',
        'description',
        'target_type',
        'target_value',
        'bonus_xp',
        'week_id',
    ];
}
