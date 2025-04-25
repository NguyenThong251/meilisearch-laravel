<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    /** @use HasFactory<\Database\Factories\ChecklistFactory> */
    use HasFactory;
    protected $fillable = [
        'content',
        'is_completed',
        'task_id',
        'subtask_id',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function subtask()
    {
        return $this->belongsTo(Subtask::class);
    }
}
