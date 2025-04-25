<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'due_date',
        'estimated_time',
        'status',
        'priority',
        'progress',
        'file_urls',
        'creator_id',
        'project_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'due_date' => 'datetime',
        'status' => 'string',
        'priority' => 'string',
        'progress' => 'integer',
        'file_urls' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_assignees')
            ->withPivot('role');
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
        ];
    }
}
