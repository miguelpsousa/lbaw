<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    // Define the table associated with the model
    protected $table = 'project_member';

    // Specify if the model should be timestamped
    public $timestamps = false;

    // Define the fillable attributes
    protected $fillable = [
        'user_id',
        'project_id',
        'role',
        'favorite',
        'invite_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}