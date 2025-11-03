<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
    // Table name
    protected $table = 'project_category';

   

    // Timestamps
    public $timestamps = false;

    // Fillable fields
    protected $fillable = [
        'name'
    ];

    // Relationships
    public function projects()
    {
        return $this->hasMany(Project::class, 'project_category_id');
    }
}
?>