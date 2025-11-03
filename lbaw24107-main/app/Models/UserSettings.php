<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    // Define the table associated with the model
    protected $table = 'user_settings';

    // Specify if the model should be timestamped
    public $timestamps = false;

    // Define the fillable attributes
    protected $fillable = [
        'user_id',
        'dark_mode',
        'task_notifications',
        'project_notifications',
        'forum_message_notifications',
    ];

    public function user()
    {
        return $this->belongsTo(Account::class, 'user_id');
    }
}