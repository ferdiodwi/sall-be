<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'module_id',
        'author_id',
        'rating',
        'comment',
        'emoji',
        'pinned',
        'teacher_reply',
    ];

    protected $casts = [
        'rating' => 'integer',
        'pinned' => 'boolean',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
