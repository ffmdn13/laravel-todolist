<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;

class User extends Authenticable
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'nickname',
        'password',
        'profile',
        'personalization',
        'date_created',
    ];

    public function taskNotes()
    {
        return $this->hasMany(TaskNote::class, 'user_id', 'id');
    }

    public function lists()
    {
        return $this->hasMany(Lists::class, 'list_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class, 'tag_id', 'id');
    }
}
