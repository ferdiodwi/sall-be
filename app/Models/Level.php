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
        'visual_guide_image',
        'visual_guide_desc',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
