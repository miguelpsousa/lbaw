<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $table = 'message';

	protected $fillable = [
		'message_text',
		'user_id',
		'project_id',
		'parent_id'
	];

	public $timestamps = true; // Enable automatic timestamps

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function project()
	{
		return $this->belongsTo(Project::class);
	}

	public function parent()
	{
		return $this->belongsTo(Message::class, 'parent_id');
	}

	public function replies()
	{
		return $this->hasMany(Message::class, 'parent_id');
	}

	public function getFormattedTimestamp()
	{
		$edited = $this->created_at != $this->updated_at;

		if ($edited) {
			$time = $this->updated_at;
		} else {
			$time = $this->created_at;
		}

		if ($time->diffInDays(Carbon::now()) > 1) {
			$format = $time->format('H:i · d/m/Y');
			return $edited ? "last edited at $format" : $format;
		}

		$diffForHumans = $time->diffForHumans();
		return $edited ? "last edited $diffForHumans" : $diffForHumans;
	}
}