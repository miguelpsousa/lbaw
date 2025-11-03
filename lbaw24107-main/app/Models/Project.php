<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'project';

    // Specify if the model should be timestamped
    public $timestamps = false;

    // Define the fillable attributes
    protected $fillable = [
        'name',
        'description',
        'status',
        'project_category_id',
        // Add other fields as necessary
    ];

    public static function find($projectId)
    {
        return static::query()->find($projectId);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_member', 'project_id', 'user_id')
                    ->withPivot('role', 'favorite','invite_status');
    }

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }
}