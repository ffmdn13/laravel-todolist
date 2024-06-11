<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
        return $this->hasMany('task_notes', 'list_id', 'id');
    }
}
