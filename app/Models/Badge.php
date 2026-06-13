<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasUuids;

    const UPDATED_AT = null;

    protected $fillable = [
        'id',
        'name',
        'emoji',
        'description',
        'requirement',
    ];

    protected $casts = [
        'requirement' => 'json',
    ];
}
