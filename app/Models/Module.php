<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'number',
        'title',
        'tagline',
        'emoji',
        'order',
        'published',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function levels()
    {
        return $this->hasMany(Level::class);
    }

    public function vocabWords()
    {
        return $this->hasMany(VocabWord::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function worksheets()
    {
        return $this->hasMany(Worksheet::class);
    }
}
