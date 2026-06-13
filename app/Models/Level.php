<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{

    protected $fillable = [
        'id',
        'module_id',
        'level',
        'content_html',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
