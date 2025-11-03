<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
	// Define the table associated with the model
	protected $table = 'task';

	// Specify if the model should be timestamped
	public $timestamps = false;

	// Define the fillable attributes
	protected $fillable = [
		'name',
		'description',
		'status',
		'project_id',
		'due_date',
		'priority',
		'creator_id',
	];

	public static function where(string $column, $value)
	{
	    return static::query()->where($column, $value);
	}

	public static function find($taskId)
	{
		return static::query()->find($taskId);
	}

	public function project()
	{
		return $this->belongsTo(Project::class, 'project_id');
	}

	public function responsibles()
	{
		return $this->belongsToMany(User::class, 'task_responsible', 'task_id', 'user_id');
	}

	public function comments()
	{
		return $this->hasMany(TaskComment::class, 'task_id', 'id');
	}
}