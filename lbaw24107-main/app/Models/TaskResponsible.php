<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TaskResponsible extends Model
{
    // Define the table associated with the model
    protected $table = 'task_responsible';

    // Specify if the model should be timestamped
    public $timestamps = false;

    // Define the fillable attributes
    protected $fillable = [
        'task_id',
        'user_id',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}