<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{

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
