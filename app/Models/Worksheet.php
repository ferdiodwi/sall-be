<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worksheet extends Model
{

    protected $fillable = [
        'id',
        'module_id',
        'title',
        'file_url',
        'format',
        'interactive',
    ];

    protected $casts = [
        'interactive' => 'boolean',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function submissions()
    {
        return $this->hasMany(WorksheetSubmission::class);
    }
}
