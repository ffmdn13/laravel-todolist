<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'color',
        'user_id'
    ];

    /**
     * Filter query by tag and user id
     */
    public function scopeByUserAndId(Builder $query, $id, $userId)
    {
        $query->where('id', $id)
            ->where('user_id', $userId);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function taskNotes()
    {
        return $this->hasMany(TaskNote::class, 'tag_id', 'id');
    }
}
