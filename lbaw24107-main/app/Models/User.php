<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;
    public $table = 'account';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'biography',
        'phone_number',
        'status',
        'profile_picture',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    
    public function admin()
    {
        return $this->hasOne(Admin::class, 'account_id', 'id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_member', 'user_id', 'project_id')
                    ->withPivot('role', 'favorite', 'invite_status');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_responsible', 'user_id', 'task_id');
    }

    // Generate a token for the invitation
    public function generateInviteToken()
    {
        return hash('sha256', $this->email . $this->id );
    }


}
