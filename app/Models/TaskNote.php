<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskNote extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table nem used for model reference
     */
    protected $table = 'task_notes';

    /**
     * Allowed column to fillabel
     */
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'reminder',
        'priority',
        'type',
        'is_complete',
        'is_trash',
        'user_id',
        'list_id',
        'tag_id',
        'notebook_id'
    ];

    /**
     * Filter query by id and user_id column
     */
    public function scopeByUserAndId(Builder $query, $id, $userId)
    {
        $query->where('id', $id)->where('user_id', $userId);
    }

    /**
     * Filter query by is_trash column with 0 value
     */
    public function scopeNotTrashed(Builder $query)
    {
        $query->where('is_trash', 0);
    }

    /**
     * Filter query by is_complete column with 0 value
     */
    public function scopeNotCompleted(Builder $query)
    {
        $query->where('is_complete', 0);
    }

    /**
     * Filter query by task column with task value
     */
    public function scopeMustTask(Builder $query)
    {
        $query->where('type', 'task');
    }

    /**
     * Filter query by task column with note value
     */
    public function scopeMustNote(Builder $query)
    {
        $query->where('type', 'note');
    }

    /**
     * Return a specific User record that relaed to specific TaskNote record
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Return a specific List record that relaed to specific TaskNote record
     */
    public function list()
    {
        return $this->belongsTo(Lists::class, 'list_id', 'id');
    }

    /**
     * Return a specific Tag record that relaed to specific TaskNote record
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id', 'id');
    }

    /**
     * Return a specific Notebook record that relaed to specific TaskNote record
     */
    public function notebooke()
    {
        return $this->belongsTo(Notebook::class, 'notebook_id', 'id');
    }
}
