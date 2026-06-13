<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasUuids;

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
