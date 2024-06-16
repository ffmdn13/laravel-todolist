<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notebook extends Model
{
    use HasFactory;

    protected $table = 'notebooks';

    protected $fillable = [
        'title',
        'user_id'
    ];

    /**
     * Filter query by tag and user id
     */
    public function scopeByUserAndId(Builder  $query, $id, $userId)
    {
        $query->where('id', $id)
            ->where('user_id', $userId);
    }

    public function taskNotes()
    {
        return $this->hasMany(TaskNote::class, 'notebook_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
