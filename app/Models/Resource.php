<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{

    protected $fillable = [
        'id',
        'module_id',
        'type',
        'title',
        'url',
        'format',
        'meta',
    ];

    protected $casts = [
        'meta' => 'json',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
