<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    use HasFactory;

    protected $table = 'lists';

    protected $fillable = [
        'title',
        'user_id'
    ];

    /**
     * Filter query by userId and id
     */
    public function scopeByUserAndId(Builder $query, $id, $userId)
    {
        $query->where('id', $id)->where('user_id', $userId);
    }

    /**
     * Filter query by list id and user id
     */
    public function scopeByListAndUser(Builder $query, $id, $userId)
    {
        $query->where('list_id', $id)->where('user_id', $userId);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function taskNotes()
    {
        return $this->hasMany(TaskNote::class, 'list_id', 'id');
    }
}
