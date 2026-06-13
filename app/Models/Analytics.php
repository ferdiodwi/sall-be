<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasUuids;

    const UPDATED_AT = null;
    const CREATED_AT = 'recorded_at';

    protected $fillable = [
        'id',
        'class_id',
        'module_id',
        'completion_rate',
        'avg_score',
        'hard_vocab',
        'hard_texts',
        'recorded_at',
    ];

    protected $casts = [
        'completion_rate' => 'float',
        'avg_score' => 'float',
        'hard_vocab' => 'json',
        'hard_texts' => 'json',
        'recorded_at' => 'datetime',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
