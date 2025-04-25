<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    /** @use HasFactory<\Database\Factories\SubtaskFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'due_date',
        'status',
        'file_urls',
        'task_id',
        'user_id',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'status' => 'string',
        'file_urls' => 'array',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }
}
