<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class WorksheetSubmission extends Model
{
    use HasUuids;

    protected $table = 'worksheet_submissions';

    protected $fillable = [
        'id',
        'worksheet_id',
        'user_id',
        'file_url',
        'html_content',
        'grade',
        'teacher_note',
        'submitted_at',
        'graded_at',
    ];

    protected $casts = [
        'grade' => 'float',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    public function worksheet()
    {
        return $this->belongsTo(Worksheet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
