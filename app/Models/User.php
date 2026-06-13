<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'role',
        'class_id',
        'level',
        'photo_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'id');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'id');
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function wordWalls()
    {
        return $this->hasMany(WordWall::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'author_id');
    }
}
