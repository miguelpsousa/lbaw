<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'username', 'password', 'email', 'biography', 'phone_number', 'status', 'profile_picture', 'remember_token', 'is_admin'
    ];

    // Specify the table name
    protected $table = 'Accounts';

    // Disable timsstamps
    public $timestamps = false;
}