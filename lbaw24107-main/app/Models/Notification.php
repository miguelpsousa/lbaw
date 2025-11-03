<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';

    public $timestamps = false;

    protected $fillable = [
        'notification_text',
        'sender_id',
        'receiver_id',
        'project_id',
        'response',
        'read_status',
        'created_at',
        'notification_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
