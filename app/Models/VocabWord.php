<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class VocabWord extends Model
{
    use HasUuids;

    protected $table = 'vocab_words';

    protected $fillable = [
        'id',
        'module_id',
        'level',
        'word',
        'meaning',
        'example',
        'emoji',
        'category',
        'order',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
