<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;

	protected $table = 'task_comment';

    protected $fillable = ['comment_text', 'user_id', 'task_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
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