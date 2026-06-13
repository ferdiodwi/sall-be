<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'subjects',
        'classes',
    ];

    protected $casts = [
        'subjects' => 'json',
        'classes' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
