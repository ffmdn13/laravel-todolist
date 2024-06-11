<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'task_count'
    ];

    public function users()
    {
        return $this->belongsTo('users', 'user_id', 'id');
    }

    public function taskNotes()
    {
        return $this->hasMany('task_notes', 'tag_id', 'id');
    }
}
